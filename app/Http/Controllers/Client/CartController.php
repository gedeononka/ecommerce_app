<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        return view('client.cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $quantity = $request->quantity ?? 1;

        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, $cartItemId)
    {
        $user = Auth::user();
        $cartItem = CartItem::where('id', $cartItemId)
                            ->where('user_id', $user->id)
                            ->firstOrFail();

        $quantity = max(1, intval($request->quantity));

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Quantité mise à jour.');
    }

    public function remove($cartItemId)
    {
        $user = Auth::user();
        $cartItem = CartItem::where('id', $cartItemId)
                            ->where('user_id', $user->id)
                            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    public function clear()
    {
        $user = Auth::user();
        CartItem::where('user_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Panier vidé.');
    }
}
