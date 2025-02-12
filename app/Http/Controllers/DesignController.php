<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Category;
use Cloudinary\Cloudinary;

class DesignController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
        ]);
    }

    public function index()
    {
        $designs = Design::with('category')->paginate(12);
        return view('designs.index', compact('designs'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('designs.create', compact('categories'));
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

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Upload to Cloudinary using the instance
                $uploadedFile = $this->cloudinary->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'designs',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);

                // Store the secure URL in validated data
                $validated['image_path'] = $uploadedFile['secure_url'];

                // Create design record in database
                $design = Design::create($validated);

                return redirect()
                    ->route('designs.manage')
                    ->with('success', 'Design created successfully!');
            }

            return back()
                ->withInput()
                ->withErrors(['image' => 'Please upload an image']);

        } catch (\Exception $e) {
            \Log::error('Design upload error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
        }
    }

    public function manage()
    {
        $designs = Design::with('category')->latest()->paginate(10);
        return view('designs.manage', compact('designs'));
    }

    public function edit(Design $design)
    {
        return view('designs.edit', compact('design'));
    }

    public function update(Request $request, Design $design)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        try {
            $design->update($validated);
            
            return redirect()
                ->route('designs.manage')
                ->with('success', 'Design updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Design update error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update design. Please try again.']);
        }
    }

    public function destroy(Design $design)
    {
        try {
            // Extract public_id from Cloudinary URL
            if ($design->image_path) {
                $pathInfo = parse_url($design->image_path);
                $pathParts = explode('/', $pathInfo['path']);
                $publicId = 'designs/' . pathinfo(end($pathParts), PATHINFO_FILENAME);
                
                // Delete from Cloudinary
                $this->cloudinary->uploadApi()->destroy($publicId);
            }

            $design->delete();

            return redirect()
                ->route('designs.manage')
                ->with('success', 'Design deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Design deletion error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to delete design. Please try again.']);
        }
    }
} 