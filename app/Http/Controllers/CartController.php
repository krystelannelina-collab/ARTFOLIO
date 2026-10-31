<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cartItems = $this->items($request);

        return view('cart', [
            'cartItems' => $cartItems,
            'subtotal' => array_sum(array_map(
                fn (array $item): float => $item['price'] * $item['quantity'],
                $cartItems,
            )),
        ]);
    }

    public function add(Request $request, string $slug): RedirectResponse
    {
        $artwork = Artwork::query()->where('slug', $slug)->firstOrFail();
        abort_unless($artwork->status === 'published', 404);
        $this->ensureCategoryIsVisible($artwork);

        $cart = $request->session()->get('cart', []);
        $cart[$artwork->id] = 1;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart')
            ->with('status', "{$artwork->title} added to your cart.");
    }

    public function buyNow(Request $request, string $slug): RedirectResponse
    {
        $artwork = Artwork::query()->where('slug', $slug)->firstOrFail();
        abort_unless($artwork->status === 'published', 404);
        $this->ensureCategoryIsVisible($artwork);

        $request->session()->put('cart', [$artwork->id => 1]);

        return redirect()->route('checkout');
    }

    public function remove(Request $request, Artwork $artwork): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$artwork->id]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart')
            ->with('status', "{$artwork->title} removed from your cart.");
    }

    /**
     * @return array<int, array{id: int, title: string, category: string, author: string, price: float, quantity: int, image: string, slug: string}>
     */
    public function items(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        if (! is_array($cart) || $cart === []) {
            return [];
        }

        return Artwork::query()
            ->whereIn('id', array_map('intval', array_keys($cart)))
            ->where('status', 'published')
            ->whereNotIn('category', Category::query()->where('is_visible', false)->select('name'))
            ->orderBy('title')
            ->get()
            ->map(fn (Artwork $artwork): array => [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'category' => $artwork->category,
                'author' => $artwork->artist_name,
                'price' => (float) $artwork->price,
                'quantity' => 1,
                'image' => asset($artwork->image_path),
                'image_path' => $artwork->image_path,
                'slug' => $artwork->slug,
            ])
            ->all();
    }

    private function ensureCategoryIsVisible(Artwork $artwork): void
    {
        abort_unless(! Category::query()
            ->where('name', $artwork->category)
            ->where('is_visible', false)
            ->exists(), 404);
    }
}
