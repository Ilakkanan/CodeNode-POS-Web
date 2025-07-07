<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'vendor']);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('barcode', 'like', "%$search%")
                ->orWhere('product_id', 'like', "%$search%")
                ->orWhereHas('category', function($q) use ($search) {
                    $q->where('category', 'like', "%$search%");
                })
                ->orWhereHas('brand', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('vendor', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }
        
        $products = $query->latest()->paginate(10)->appends(['search' => $request->search]);
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $vendors = Vendor::all();
        return view('products.create', compact('categories', 'brands', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'vendor_id' => 'required|exists:vendors,id',
            'unit_price' => 'required|numeric|min:0'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $vendors = Vendor::all();
        return view('products.edit', compact('product', 'categories', 'brands', 'vendors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'vendor_id' => 'required|exists:vendors,id',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product moved to trash');
    }

    public function trashed()
    {
        $search = request('search');
        $products = Product::onlyTrashed()
                    ->with(['category', 'brand', 'vendor'])
                    ->when($search, function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('barcode', 'like', "%{$search}%");
                    })
                    ->paginate(10);
        
        return view('products.trashed', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trashed')
            ->with('success', 'Product restored successfully');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('products.trashed')
            ->with('success', 'Product permanently deleted');
    }
}
