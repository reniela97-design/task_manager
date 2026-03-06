<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required|string|max:255']);

        Category::create([
            'category_name' => $request->category_name,
            'category_user_id' => auth()->id(), // Assigns logged-in user
            'category_log_datetime' => now(),
            'category_inactive' => 0,
        ]);

        return redirect()->route('categories.index')->with('status', 'Category added successfully.');
    }

    public function destroy($id)
    {
        // Explicitly find by your custom primary key 'category_id'
        $category = Category::where('category_id', $id)->firstOrFail();
        $category->delete();
        
        return redirect()->route('categories.index')->with('status', 'Category deleted.');
    }
    // Add this method to your existing CategoryController class
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255'
        ]);

        // Find the category by your custom ID
        $category = Category::where('category_id', $id)->firstOrFail();

        $category->update([
            'category_name' => $request->category_name,
            // We can also update the status if you want (active/inactive)
             'category_inactive' => $request->has('category_inactive') ? 1 : 0,
        ]);

        return redirect()->route('categories.index')->with('status', 'Category updated successfully.');
    }
}