<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Design;
use Cloudinary\Cloudinary;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::with('category')->paginate(12);
        return view('designs.index', compact('designs'));
    }

    public function create()
    {
        return view('designs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        // Upload image to Cloudinary
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $result = cloudinary()->upload($image->getRealPath())->getSecurePath();
            $validated['image_path'] = $result;
        }

        Design::create($validated);
        return redirect()->route('designs.manage')->with('success', 'Design created successfully!');
    }

    public function manage()
    {
        $designs = Design::with('category')->latest()->paginate(10);
        return view('designs.manage', compact('designs'));
    }

    public function update(Request $request, Design $design)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        // Upload new image to Cloudinary if provided
        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($design->image_path) {
                // Extract public_id from the URL
                $public_id = substr(strrchr(rtrim($design->image_path, "/"), "/"), 1);
                cloudinary()->destroy($public_id);
            }
            
            $image = $request->file('image');
            $result = cloudinary()->upload($image->getRealPath())->getSecurePath();
            $validated['image_path'] = $result;
        }

        $design->update($validated);
        return redirect()->route('designs.manage')->with('success', 'Design updated successfully!');
    }

    public function destroy(Design $design)
    {
        // Delete image from Cloudinary if exists
        if ($design->image_path) {
            // Extract public_id from the URL
            $public_id = substr(strrchr(rtrim($design->image_path, "/"), "/"), 1);
            cloudinary()->destroy($public_id);
        }

        $design->delete();
        return redirect()->route('designs.manage')->with('success', 'Design deleted successfully!');
    }
} 