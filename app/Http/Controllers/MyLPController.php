<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Models\Product;
use App\Models\AddOn;

class MyLPController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $landingPage = LandingPage::firstOrCreate(
            ['user_id' => $user->id],
            ['title' => 'My Digital Product', 'slug' => \Illuminate\Support\Str::slug($user->name . '-' . uniqid())]
        );

        // Fetch products
        $products = Product::where('landing_page_id', $landingPage->id)->with('addOns')->get();
        if($products->isEmpty()) {
            $product = Product::create([
                'landing_page_id' => $landingPage->id,
                'name' => 'Default Product',
                'price' => 0
            ]);
            $products->push($product);
        }
        
        return view('mylp.index', compact('landingPage', 'products'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $landingPage->title = $request->title;
        $landingPage->description = $request->description;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing_pages', 'public');
            $landingPage->image_path = $path;
        }

        $landingPage->save();

        return redirect()->route('my-lp.index')->with('status', 'Landing page updated!');
    }

    public function storeProduct(Request $request)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'download_url' => 'nullable|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'is_unlimited_qty' => 'nullable|boolean',
            'qty' => 'nullable|numeric',
            'limit_per_checkout' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'file_upload' => 'nullable|file|max:51200', // 50MB max for zip
        ]);

        $productData = $request->only([
            'name', 'description', 'type', 'download_url', 'price', 
            'sale_price', 'is_unlimited_qty', 'qty', 'limit_per_checkout'
        ]);
        
        $productData['is_unlimited_qty'] = $request->has('is_unlimited_qty') ? 1 : 0;
        $productData['landing_page_id'] = $landingPage->id;

        if ($request->hasFile('image')) {
            $productData['image_path'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('file_upload')) {
            $productData['file_path'] = $request->file('file_upload')->store('product_files', 'public');
        }

        if ($request->product_id) {
            $product = Product::where('landing_page_id', $landingPage->id)->findOrFail($request->product_id);
            $product->update($productData);
            $msg = 'Product updated!';
        } else {
            Product::create($productData);
            $msg = 'Product added!';
        }

        return redirect()->route('my-lp.index')->with('status', $msg);
    }

    public function destroyProduct(Request $request, $id)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();
        $product = Product::where('landing_page_id', $landingPage->id)->findOrFail($id);
        $product->delete();

        return redirect()->route('my-lp.index')->with('status', 'Product deleted!');
    }

    public function storeAddOn(Request $request)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();
        $product = Product::where('landing_page_id', $landingPage->id)->findOrFail($request->product_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'download_url' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'file_upload' => 'nullable|file|max:51200',
        ]);

        $addonData = [
            'product_id' => $product->id,
            'name' => $request->name,
            'type' => $request->type,
            'download_url' => $request->download_url,
            'price' => $request->price
        ];

        if ($request->hasFile('image')) {
            $addonData['image_path'] = $request->file('image')->store('addons', 'public');
        }

        if ($request->hasFile('file_upload')) {
            $addonData['file_path'] = $request->file('file_upload')->store('addon_files', 'public');
        }

        AddOn::create($addonData);

        return redirect()->route('my-lp.index')->with('status', 'Add-on created!');
    }

    public function destroyAddOn(Request $request, $id)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();
        
        $addon = AddOn::whereHas('product', function($q) use ($landingPage) {
            $q->where('landing_page_id', $landingPage->id);
        })->findOrFail($id);
        
        $addon->delete();

        return redirect()->route('my-lp.index')->with('status', 'Add-on deleted!');
    }
}
