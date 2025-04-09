<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Design;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['user', 'items.design'])->latest();

        if ($user->isBuyer()) {
            // Buyers only see their own orders
            $query->where('user_id', $user->id);
        }
        // Admins see all orders (no additional where clause)

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        // Preserve status filter in pagination links
        if ($request->has('status')) {
            $orders->appends(['status' => $request->status]);
        }

        // Count pending orders for admin notification
        $pendingOrdersCount = 0;
        if ($user->isAdmin()) {
            $pendingOrdersCount = Order::pending()->count();
        }

        return view('orders.index', compact('orders', 'pendingOrdersCount'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        // Check if the user is an admin or if the order belongs to the buyer
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.'); // Or redirect with an error
        }

        $order->load(['user', 'items.design']);
        return view('orders.show', compact('order'));
    }

    /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to update order status.');
        }

        // Get the old status for messaging
        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Update the order status
        $order->update($validated);

        // Create a success message based on the status change
        $message = 'Order status updated successfully.';

        if ($oldStatus === 'pending' && $newStatus === 'processing') {
            $message = 'Order confirmed successfully. The customer can now download their designs.';
        }

        return redirect()->route('orders.index')->with('success', $message);
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->latest()
                      ->paginate(10);
        return view('orders.history', compact('orders'));
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)
                         ->where('user_id', auth()->id())
                         ->first();
        }
        return view('orders.track', compact('order'));
    }

    /**
     * Download all designs from an order as a zip file.
     *
     * @param  \App\Models\Order  $order
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAllDesigns(Order $order)
    {
        $user = Auth::user();

        // Check if the user is authorized to download designs from this order
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order has any items
        if ($order->items->isEmpty()) {
            abort(404, 'No designs found in this order.');
        }

        // Check if the order status allows downloads
        if ($order->status === 'pending' || $order->status === 'cancelled') {
            if (!$user->isAdmin()) { // Admins can always download
                abort(403, 'This order has not been confirmed yet. Downloads will be available once the order is confirmed.');
            }
        }

        // Update order status if it's in 'processing' state
        if ($order->status === 'processing') {
            // Update to 'delivered' status (using 'delivered' instead of 'completed' to match the database schema)
            $order->status = 'delivered';
            $order->save();

            // Log the status change
            \Log::info('Order #' . $order->order_number . ' status updated to delivered after designs download');
        }

        // Create a temporary directory for the zip file
        $tempDir = storage_path('app/temp/' . uniqid());
        if (!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0755, true)) {
                abort(500, 'Could not create temporary directory for ZIP file.');
            }
        }

        // Make sure the directory is writable
        if (!is_writable($tempDir)) {
            chmod($tempDir, 0755);
            if (!is_writable($tempDir)) {
                abort(500, 'Temporary directory is not writable.');
            }
        }

        // Create a new zip archive
        $zipFileName = 'order_' . $order->order_number . '_designs.zip';
        $zipFilePath = $tempDir . '/' . $zipFileName;

        // Check if ZipArchive is available
        if (!class_exists('\ZipArchive')) {
            $this->cleanupTempDir($tempDir);
            abort(500, 'ZIP functionality is not available on this server.');
        }

        $zip = new \ZipArchive();
        $result = $zip->open($zipFilePath, \ZipArchive::CREATE);
        if ($result !== true) {
            $this->cleanupTempDir($tempDir);
            abort(500, 'Could not create zip file. Error code: ' . $result);
        }

        // Add each design to the zip file
        $hasErrors = false;
        $errorMessages = [];

        foreach ($order->items as $item) {
            $design = $item->design;

            try {
                // Check if the image_path is a URL
                if (filter_var($design->image_path, FILTER_VALIDATE_URL)) {
                    // Download the image
                    $imageContents = file_get_contents($design->image_path);

                    if ($imageContents === false) {
                        $errorMessages[] = 'Unable to download design: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }

                    // Generate a filename based on the design title
                    $filename = Str::slug($design->title) . '.png';

                    // Save the image to the temporary directory
                    $tempFilePath = $tempDir . '/' . $filename;
                    if (file_put_contents($tempFilePath, $imageContents) === false) {
                        $errorMessages[] = 'Failed to save design file: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }

                    // Add the file to the zip archive using addFromString instead of addFile
                    // This avoids file locking issues
                    if (!$zip->addFromString($filename, $imageContents)) {
                        $errorMessages[] = 'Failed to add design to zip: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }

                    // Clean up the temp file immediately after adding to zip
                    if (file_exists($tempFilePath)) {
                        @unlink($tempFilePath);
                    }
                } else {
                    // For local files
                    $filePath = public_path($design->image_path);

                    if (!file_exists($filePath)) {
                        $errorMessages[] = 'Design file not found: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }

                    // Generate a filename based on the design title
                    $filename = Str::slug($design->title) . '.png';

                    // Read the file contents
                    $fileContents = @file_get_contents($filePath);
                    if ($fileContents === false) {
                        $errorMessages[] = 'Failed to read design file: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }

                    // Add the file to the zip archive using addFromString
                    if (!$zip->addFromString($filename, $fileContents)) {
                        $errorMessages[] = 'Failed to add design to zip: ' . $design->title;
                        $hasErrors = true;
                        continue;
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Design download failed: ' . $e->getMessage());
                $errorMessages[] = 'Error processing design: ' . $design->title;
                $hasErrors = true;
            }
        }

        // Close the zip archive
        if (!$zip->close()) {
            \Log::error('Failed to close ZIP file: ' . $zipFilePath);
            $this->cleanupTempDir($tempDir);
            abort(500, 'Failed to create ZIP file. Please try again.');
        }

        // Check if the ZIP file was created and has content
        if (!file_exists($zipFilePath) || filesize($zipFilePath) === 0) {
            $this->cleanupTempDir($tempDir);
            abort(500, 'No design files could be added to the zip archive.');
        }

        try {
            // Return the zip file for download
            // We'll use a simpler approach that's less likely to cause issues
            $response = response()->download($zipFilePath, $zipFileName);

            // Register a cleanup callback that will run after the response is sent
            register_shutdown_function(function() use ($tempDir, $zipFilePath) {
                // Wait a moment to ensure the file is fully sent
                sleep(1);

                // Delete the zip file first
                if (file_exists($zipFilePath)) {
                    @unlink($zipFilePath);
                }

                // Then try to remove the directory
                if (is_dir($tempDir)) {
                    @rmdir($tempDir);
                }
            });

            return $response;
        } catch (\Exception $e) {
            \Log::error('Error sending ZIP file: ' . $e->getMessage());
            $this->cleanupTempDir($tempDir);
            abort(500, 'Error sending ZIP file. Please try again.');
        }
    }

    /**
     * Helper method to clean up a temporary directory
     *
     * @param string $dir Directory path to clean up
     * @return void
     */
    public function cleanupTempDir($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        try {
            // First, make sure we have proper permissions
            chmod($dir, 0755);

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                $path = $file->getRealPath();

                try {
                    if ($file->isDir()) {
                        chmod($path, 0755);
                        if (!rmdir($path)) {
                            \Log::warning("Could not remove directory: {$path}");
                        }
                    } else {
                        chmod($path, 0644);
                        if (!unlink($path)) {
                            \Log::warning("Could not remove file: {$path}");
                        }
                    }
                } catch (\Exception $ex) {
                    \Log::warning("Error removing {$path}: {$ex->getMessage()}");
                }
            }

            // Try to remove the main directory
            if (is_dir($dir)) {
                if (!rmdir($dir)) {
                    \Log::warning("Could not remove main directory: {$dir}");
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to clean up temp directory: ' . $e->getMessage());
        }
    }

    /**
     * Download a design file from an order.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Design  $design
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadDesign(Order $order, Design $design)
    {
        $user = Auth::user();

        // Check if the user is authorized to download this design
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order contains this design
        $orderItem = $order->items()->where('design_id', $design->id)->first();
        if (!$orderItem) {
            abort(404, 'Design not found in this order.');
        }

        // Check if the order status allows downloads
        if ($order->status === 'pending' || $order->status === 'cancelled') {
            if (!$user->isAdmin()) { // Admins can always download
                abort(403, 'This order has not been confirmed yet. Downloads will be available once the order is confirmed.');
            }
        }

        // Note: 'processing' and 'completed' statuses are allowed to download

        // Update order status if it's in 'processing' state
        if ($order->status === 'processing') {
            // Update to 'delivered' status (using 'delivered' instead of 'completed' to match the database schema)
            $order->status = 'delivered';
            $order->save();

            // Log the status change
            \Log::info('Order #' . $order->order_number . ' status updated to delivered after design download');
        }

        // Check if the image_path is a URL
        if (filter_var($design->image_path, FILTER_VALIDATE_URL)) {
            // For external URLs, download the image and serve it as a download
            try {
                $imageContents = file_get_contents($design->image_path);

                if ($imageContents === false) {
                    abort(404, 'Unable to download the design file.');
                }

                // Generate a filename based on the design title
                $filename = Str::slug($design->title) . '.png';

                // Return the image as a downloadable file
                return response($imageContents)
                    ->header('Content-Type', 'image/png')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

            } catch (\Exception $e) {
                \Log::error('Design download failed: ' . $e->getMessage());
                abort(500, 'Error downloading the design file.');
            }
        } else {
            // For local files
            $filePath = public_path($design->image_path);

            // Check if file exists
            if (!file_exists($filePath)) {
                abort(404, 'Design file not found.');
            }

            // Return the file for download
            return response()->download($filePath, Str::slug($design->title) . '.png');
        }
    }
}
