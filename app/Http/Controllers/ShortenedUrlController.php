<?php
namespace App\Http\Controllers;

use App\Models\ShortenedUrl;
use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;
class ShortenedUrlController extends Controller
{
    private function shortDomain(): string
    {
        return rtrim((string) config('app.short_url_domain'), '/');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url'  => 'required|url',
            'shortened_url' => 'required|string|unique:shortened_urls,shortened_url',
            'expires_at'    => 'required',
        ]);
        
        // Clean the `expires_at` value by removing the timezone name
        $expiresAtRaw = $validated['expires_at'] ?? null;
        if ($expiresAtRaw) {
            // Remove the part of the string after the timezone offset (e.g., "(India Standard Time)")
            $expiresAtRaw = preg_replace('/\s*\([^\)]*\)$/', '', $expiresAtRaw);  // Remove everything after the timezone name
    
            // Parse the cleaned date using Carbon
            $expiresAt = Carbon::parse($expiresAtRaw)->toIso8601String();  // Convert to ISO 8601 format
        } else {
            // Default to 1 year from now if not provided
            $expiresAt = Carbon::now()->addYears(1)->toIso8601String();
        }

        $shortenedUrl                = new ShortenedUrl();
        $shortenedUrl->original_url  = $validated['original_url'];
        $shortenedUrl->shortened_url = $validated['shortened_url'];
        $shortenedUrl->expires_at    = $expiresAt;
        $shortenedUrl->ip            = $request->ip();
        $shortenedUrl->save();

        return response()->json(['message' => 'Shortened URL saved successfully!'], 200);
    }

    // Method to get the original URL based on the code
    public function get($code)
    {
        // Find the shortened URL entry by the code
        $shortenedUrl = ShortenedUrl::where('shortened_url', $this->shortDomain() . '/' . $code)->first();

        // If the shortened URL doesn't exist or is expired, show an error page
        if (! $shortenedUrl || new \DateTime() > new \DateTime($shortenedUrl->expires_at)) {
            // You can redirect to an error page or show a message
            return response()->json(['error' => 'URL has expired or does not exist.'], 404);
        }

        $shortenedUrl->increment('clicks');

        // Redirect to the original URL
        return redirect()->to($shortenedUrl->original_url);
    }


     public function developerShorten(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'original_url' => 'required|url',   // Ensure that the URL is valid
            'expires_at'   => 'nullable',  // Optional expiration date
        ]);

        // Generate a unique shortened code
        $code = Str::random(6);  // You can adjust the length of the shortened code

        // Set expiration date (default to 1 year from now if not provided)
        $expiresAt = $validated['expires_at'] ?? Carbon::now()->addYears(1000);

        // Create the shortened URL in the database
        $shortenedUrl = ShortenedUrl::create([
            'original_url' => $validated['original_url'],
            'shortened_url' => $this->shortDomain() . '/' . $code,
            'expires_at' => $expiresAt,
            'clicks' => 0,  // Initialize click count as 0
        ]);

        // Return the shortened URL and other details in the response
        return response()->json([
            'shortened_url' => $shortenedUrl->shortened_url,
            'code' => $code,
            'original_url'  => $shortenedUrl->original_url,
            'expires_at'    => $shortenedUrl->expires_at,
        ]);
    }

}
