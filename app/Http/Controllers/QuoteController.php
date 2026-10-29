<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    /**
     * The number of quotes shown on each page of the feed.
     */
    public const PER_PAGE = 9;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort') === 'brightest' ? 'brightest' : 'newest';

        $query = Quote::with('user')
            ->withCount('likes')
            ->withExists(['likes as liked_by_user' => fn ($query) => $query->where('users.id', Auth::id())]);

        if ($sort === 'brightest') {
            $query->orderByDesc('likes_count');
        }

        $quotes = $query->latest()->orderByDesc('id')->paginate(self::PER_PAGE)->withQueryString();

        if ($quotes->isEmpty() && $quotes->currentPage() > 1) {
            return redirect()->to($quotes->url($quotes->lastPage()));
        }

        return view('welcome', compact('quotes', 'sort'));
    }

    public function toggleLike(Quote $quote)
    {
        $user = Auth::user();
        $liked = $quote->likes()->where('users.id', $user->id)->exists();

        if ($liked) {
            $quote->likes()->detach($user->id);
        } else {
            $quote->likes()->attach($user->id);
        }

        return redirect()->to($this->feedUrl('quote-'.$quote->id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuoteRequest $request)
    {
        $quote = Auth::user()->quotes()->create($request->validated());
        return redirect()->to(route('home').'#quote-'.$quote->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuoteRequest $request, Quote $quote)
    {
        abort_if($quote->user_id != Auth::id(), 403);

        $quote->update($request->validated());
        return redirect()->to($this->feedUrl('quote-'.$quote->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Quote $quote)
    {
        abort_if($quote->user_id != Auth::id(), 403);
        $neighborId = $request->integer('return_quote_id');
        if ($neighborId === $quote->id || ! Quote::whereKey($neighborId)->exists()) {
            $neighborId = null;
        }
        $quote->delete();

        return redirect()->to($this->feedUrl($neighborId ? 'quote-'.$neighborId : 'quotes'));
    }

    /**
     * Build the feed URL for the page and sort the visitor came from.
     */
    private function feedUrl(string $anchor): string
    {
        parse_str((string) parse_url(url()->previous(), PHP_URL_QUERY), $previousQuery);

        $parameters = array_filter([
            'sort' => ($previousQuery['sort'] ?? null) === 'brightest' ? 'brightest' : null,
            'page' => max(1, (int) ($previousQuery['page'] ?? 1)) > 1 ? (int) $previousQuery['page'] : null,
        ]);

        return route('home', $parameters).'#'.$anchor;
    }
}
