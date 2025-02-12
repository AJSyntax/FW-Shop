<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        // Store category logic here

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        // Update category logic here

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        // Delete category logic here

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
} 