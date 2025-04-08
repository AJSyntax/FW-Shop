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
        // Revert to original Cloudinary setup
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

        $uploadedImagePath = null;
        $uploadWarning = null;

        // 1. Attempt Cloudinary Upload and capture result/warning
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                \Log::info("Attempting Cloudinary upload for: " . $validated['title']);
                $uploadedFile = $this->cloudinary->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'designs',
                    'transformation' => ['quality' => 'auto', 'fetch_format' => 'auto']
                ]);
                $uploadedImagePath = $uploadedFile['secure_url'];
                \Log::info("Cloudinary upload successful: " . $uploadedImagePath);
            } catch (\Throwable $e) { // Catch Throwable for maximum safety
                \Log::error("Cloudinary upload failed: " . $e->getMessage());
                $uploadWarning = 'Design saved to database, but image upload failed (likely SSL issue in local env). Error: ' . $e->getMessage();
                // Ensure $uploadedImagePath remains null
                $uploadedImagePath = null; 
            }
        } else {
            // This case should ideally be caught by validation, but return error if reached
            return back()->withInput()->withErrors(['image' => 'Image file is required.']);
        }

        // 2. Save to Database (inside transaction)
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $dbData = $validated;
            $dbData['image_path'] = $uploadedImagePath; // Use path from upload attempt (could be null)
            $dbData['is_active'] = true;

            \Log::info("Saving design to DB. Image path: " . ($dbData['image_path'] ?? 'null'));
            $design = Design::create($dbData);
            \Log::info("Design ID {$design->id} saved to DB.");

            \Illuminate\Support\Facades\DB::commit();

        } catch (\Exception $e) { // Catch only DB exceptions here
            \Illuminate\Support\Facades\DB::rollBack();
            \Log::error('Database save error during design store: ' . $e->getMessage());
            // Return specific DB error
            return back()->withInput()->withErrors(['error' => 'Failed to save design to database. Error: ' . $e->getMessage()]);
        }

        // 3. Redirect with appropriate messages (success + potential warning)
        $redirect = redirect()->route('admin.designs.manage')->with('success', 'Design saved successfully!');
        if ($uploadWarning) {
            $redirect->with('warning', $uploadWarning);
        }
        return $redirect;
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
                ->route('admin.designs.manage') // Use admin prefix
                ->with('success', 'Design updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Design update error: ' . $e->getMessage());
            
            // Revert to generic error message
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update design. Please try again.']); 
        }
    }

    public function destroy(Design $design)
    {
        // Use DB transaction for atomicity
        \Illuminate\Support\Facades\DB::beginTransaction(); 
        try {
            $publicId = null; // Initialize publicId
            $destroyWarning = null; // Flag for destroy warning

            // Attempt to delete from Cloudinary first, but catch errors separately
            if ($design->image_path) {
                try {
                    $pathInfo = parse_url($design->image_path);
                    // Basic check for Cloudinary URL structure
                    if (isset($pathInfo['host']) && str_contains($pathInfo['host'], 'cloudinary.com') && isset($pathInfo['path'])) {
                         $pathParts = explode('/', trim($pathInfo['path'], '/'));
                         // Assuming format like /v12345/designs/filename.jpg - adjust if needed
                         if (count($pathParts) >= 3 && $pathParts[count($pathParts)-2] === 'designs') {
                             $publicId = 'designs/' . pathinfo(end($pathParts), PATHINFO_FILENAME);
                             \Log::info("Attempting to delete Cloudinary image: {$publicId} for design ID: {$design->id}");
                             $this->cloudinary->uploadApi()->destroy($publicId);
                             \Log::info("Successfully deleted Cloudinary image: {$publicId}");
                         } else {
                              \Log::warning("Could not determine Cloudinary public ID from path: " . $design->image_path);
                         }
                    } else {
                         \Log::warning("Image path does not appear to be a Cloudinary URL: " . $design->image_path);
                    }

                } catch (\GuzzleHttp\Exception\RequestException $guzzleException) { // Catch Guzzle exceptions specifically
                    \Log::error("Cloudinary deletion failed (Guzzle) for design ID {$design->id}, public ID {$publicId}: " . $guzzleException->getMessage());
                    $destroyWarning = 'Design deleted from database, but failed to remove image from Cloudinary (Network/SSL Issue). Error: ' . $guzzleException->getMessage();
                } catch (\Exception $cloudinaryException) { // Catch other Cloudinary exceptions AFTER specific ones
                    \Log::error("Cloudinary deletion failed for design ID {$design->id}, public ID {$publicId}: " . $cloudinaryException->getMessage());
                    $destroyWarning = 'Design deleted from database, but failed to remove image from Cloudinary. Error: ' . $cloudinaryException->getMessage();
                }
            }

            // Delete from database
            \Log::info("Attempting to delete design ID: {$design->id} from database");
            $design->delete();
            \Log::info("Successfully deleted design ID: {$design->id} from database");

            \Illuminate\Support\Facades\DB::commit(); // Commit transaction

            $redirect = redirect()->route('admin.designs.manage')->with('success', 'Design deleted successfully!');
             if ($destroyWarning) {
                // Add the warning message if the destroy failed
                $redirect->with('warning', $destroyWarning); 
            }
            return $redirect;
            
        } catch (\Exception $e) { // Catch errors during DB deletion
            \Illuminate\Support\Facades\DB::rollBack(); // Rollback transaction on error
            \Log::error('Design database deletion error for ID ' . $design->id . ': ' . $e->getMessage()); 
            
            // Return generic error message for database issues
            return back()->withErrors(['error' => 'Failed to delete design from database. Please try again. Error: ' . $e->getMessage()]); 
        }
    }
}
