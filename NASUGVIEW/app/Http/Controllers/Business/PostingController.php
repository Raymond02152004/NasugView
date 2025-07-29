<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessPost;
use App\Models\Service;
use App\Models\Product;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    /**
     * Store a new service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postService(Request $request, $id)
    {
        // Validate the service data
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_description' => 'required|string|max:1000',
        ]);

        // Find the business post by ID
        $post = BusinessPost::findOrFail($id);

        // Create a new service for the business post
        $service = $post->services()->create([
            'name' => $request->service_name,
            'description' => $request->service_description,
        ]);

        // Redirect with success message
        return redirect()->route('business.show', ['id' => $post->id])
                         ->with('success', 'Service posted successfully!');
    }

    /**
     * Store a new product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postProduct(Request $request, $id)
    {
        // Validate the product data
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string|max:1000',
        ]);

        // Find the business post by ID
        $post = BusinessPost::findOrFail($id);

        // Create a new product for the business post
        $product = $post->products()->create([
            'name' => $request->product_name,
            'description' => $request->product_description,
        ]);

        // Redirect with success message
        return redirect()->route('business.show', ['id' => $post->id])
                         ->with('success', 'Product posted successfully!');
    }

    /**
     * Store a new menu item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postMenu(Request $request, $id)
    {
        // Validate the menu item data
        $validated = $request->validate([
            'menu_item_name' => 'required|string|max:255',
            'menu_item_description' => 'required|string|max:1000',
        ]);

        // Find the business post by ID
        $post = BusinessPost::findOrFail($id);

        // Create a new menu item for the business post
        $menuItem = $post->menuItems()->create([
            'name' => $request->menu_item_name,
            'description' => $request->menu_item_description,
        ]);

        // Redirect with success message
        return redirect()->route('business.show', ['id' => $post->id])
                         ->with('success', 'Menu item posted successfully!');
    }
}
