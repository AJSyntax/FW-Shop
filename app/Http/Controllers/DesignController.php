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
            // Add this section for Guzzle configuration
            'url' => [
                'secure' => true, // Keep using HTTPS
                // Disable SSL verification for local development ONLY
                'secure_distribution' => null, 
                'private_cdn' => false,
                'cname' => null,
                'secure_cdn_subdomain' => false,
                'cdn_subdomain' => false,
            ],
            'api' => [
                'upload_options' => [
                    // Pass Guzzle client options here (keep for good measure)
                    'client_config' => [
                        'verify' => false, 
                    ],
                    // Add direct cURL options for upload
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false, // Use false (or 0)
                    ],
                ],
                // Also apply to general API calls if needed, though upload is primary
                 'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ],
            ],
        ]);
    }

    public function index(Request $request)
    {
        $query = Design::query()->with('category')->where('is_active', true); // Start query, only active designs

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by Category
        if ($request->filled('category') && $request->input('category') != '') {
            $query->where('category_id', $request->input('category'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest'); // Default to newest
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $designs = $query->paginate(12)->withQueryString(); // Paginate and append query string

        $categories = Category::where('is_active', true)->orderBy('name')->get(); // Get active categories for filter

        return view('designs.index', compact('designs', 'categories', 'request')); // Pass request for old input
    }

    /**
     * Display the specified design.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\View\View
     */
    public function show(Design $design)
    {
        // Ensure the design is active or handle appropriately if needed
        // if (!$design->is_active) {
        //     abort(404); // Or redirect, or show a specific message
        // }
        return view('designs.show', compact('design'));
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
                // Define upload options, including disabling SSL verification for this specific call
                $uploadOptions = [
                    'folder' => 'designs',
                    'transformation' => ['quality' => 'auto', 'fetch_format' => 'auto'],
                    // Add cURL options directly here for the upload call
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false, // Use 0 or false
                    ],
                ];
                $uploadedFile = $this->cloudinary->uploadApi()->upload($image->getRealPath(), $uploadOptions);
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
