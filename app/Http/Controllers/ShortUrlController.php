<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exports\ShortUrlsExport;
use Maatwebsite\Excel\Facades\Excel;

class ShortUrlController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {

            $shortUrls = ShortUrl::with(['user', 'company'])
                ->latest()
                ->paginate(20);
        } elseif ($user->isAdmin()) {

            $shortUrls = ShortUrl::where('company_id', $user->company_id)
                ->with('user')
                ->latest()
                ->paginate(20);
        } elseif ($user->isMember()) {

            $shortUrls = ShortUrl::where('user_id', $user->id)
                ->with('company')
                ->latest()
                ->paginate(20);
        } else {
            $shortUrls = collect();
        }

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'SuperAdmin cannot create short URLs.');
        }

        if (!$user->can('create_short_url')) {
            return redirect()->back()->with('error', 'You do not have permission to create short URLs.');
        }

        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url|max:500',
            'custom_code' => 'nullable|alpha_dash|min:3|max:20|unique:short_urls,short_code',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shortCode = $request->custom_code ?? $this->generateUniqueCode();

        $shortUrl = ShortUrl::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('short-urls.index')
            ->with('success', 'Short URL created successfully!')
            ->with('short_url', $shortUrl->short_url);
    }

    public function show(ShortUrl $shortUrl)
    {
        $user = Auth::user();

        // Authorization
        if ($user->isSuperAdmin() ||
            ($user->isAdmin() && $shortUrl->company_id == $user->company_id) ||
            ($user->isMember() && $shortUrl->user_id == $user->id)) {

            return view('short-urls.show', compact('shortUrl'));
        }

        abort(403, 'Unauthorized action.');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        $user = Auth::user();

        if ($shortUrl->user_id != $user->id &&
            !($user->isAdmin() && $shortUrl->company_id == $user->company_id)) {
            abort(403, 'Unauthorized action.');
        }

        $shortUrl->delete();

        return redirect()->route('short-urls.index')
            ->with('success', 'Short URL deleted successfully.');
    }

    private function generateUniqueCode($length = 6)
    {
        do {
            $code = Str::random($length);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }

    public function redirect($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        if ($shortUrl->isExpired()) {
            abort(404, 'This short URL has expired.');
        }

        $shortUrl->incrementClicks();

        return redirect()->away($shortUrl->original_url);
    }


    public function export()
{
    $user = Auth::user();

    if ($user->isSuperAdmin()) {
        $shortUrls = ShortUrl::with(['user', 'company'])->latest()->get();
    }
    elseif ($user->isAdmin()) {
        $shortUrls = ShortUrl::where('company_id', $user->company_id)
            ->with('user')
            ->latest()
            ->get();
    }
    elseif ($user->isMember()) {
        $shortUrls = ShortUrl::where('user_id', $user->id)
            ->with('company')
            ->latest()
            ->get();
    }
    else {
        $shortUrls = collect();
    }

    return Excel::download(
        new ShortUrlsExport($shortUrls),
        'short_urls.xlsx'
    );
}
}
