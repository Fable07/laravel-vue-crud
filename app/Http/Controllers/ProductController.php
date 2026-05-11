<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->trashed === 'only') {
            $query->onlyTrashed();
        } elseif ($request->trashed === 'with') {
            $query->withTrashed();
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters'  => $request->only(['search', 'trashed']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(Request $request)
    {
        $userId   = Auth::id();
        $category = $request->input('category') ?: null;
        $status   = $request->input('status', 'active');

        $nameRules = ['required', 'string', 'max:255'];
        if ($status === 'active') {
            $nameRules[] = Rule::unique('products', 'name')
                ->where(function ($query) use ($userId, $category) {
                    $query->where('user_id', $userId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at');
                    if ($category !== null) {
                        $query->where('category', $category);
                    } else {
                        $query->whereNull('category');
                    }
                });
        }

        $validated = $request->validate([
            'name'        => $nameRules,
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'status'      => 'nullable|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Auth::user()->products()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'quantity'    => $validated['quantity'],
            'category'    => $validated['category'] ?? null,
            'status'      => $validated['status'] ?? 'active',
            'image_path'  => $imagePath,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        $product = Product::withTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return Inertia::render('Products/Show', ['product' => $product]);
    }

    public function edit(string $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        return Inertia::render('Products/Edit', ['product' => $product]);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $userId   = Auth::id();
        $category = $request->input('category') ?: null;
        $status   = $request->input('status', $product->status);

        $nameRules = ['required', 'string', 'max:255'];
        if ($status === 'active') {
            $nameRules[] = Rule::unique('products', 'name')
                ->where(function ($query) use ($userId, $category) {
                    $query->where('user_id', $userId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at');
                    if ($category !== null) {
                        $query->where('category', $category);
                    } else {
                        $query->whereNull('category');
                    }
                })
                ->ignore($product->id);
        }

        $validated = $request->validate([
            'name'        => $nameRules,
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'status'      => 'nullable|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'quantity'    => $validated['quantity'],
            'category'    => $validated['category'] ?? null,
            'status'      => $validated['status'] ?? $product->status,
            'image_path'  => $validated['image_path'] ?? $product->image_path,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product moved to trash.');
    }

    public function restore(string $id)
    {
        $product = Product::onlyTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($product->status === 'active') {
            $conflict = Product::where('user_id', Auth::id())
                ->where('name', $product->name)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->when(
                    $product->category !== null,
                    fn($q) => $q->where('category', $product->category),
                    fn($q) => $q->whereNull('category')
                )
                ->exists();

            if ($conflict) {
                $category = $product->category ?? 'uncategorized';
                return redirect()
                    ->back()
                    ->with('error', "Cannot restore: An active product named '{$product->name}' already exists in the '{$category}' category. Please rename the existing product first.");
            }
        }

        $product->restore();

        return redirect()->route('products.index', ['trashed' => 'only'])
            ->with('success', 'Product restored successfully.');
    }

    public function forceDelete(string $id)
    {
        $product = Product::onlyTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->forceDelete();

        return redirect()->route('products.index', ['trashed' => 'only'])
            ->with('success', 'Product permanently deleted.');
    }
}
