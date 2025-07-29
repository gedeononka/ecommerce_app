<?php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CartApiController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cartItems()->with('product')->get();
        return response()->json($cart);
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $quantity = max(1, intval($request->input('quantity', 1)));

        $cartItem = auth()->user()->cartItems()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            auth()->user()->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json(['message' => 'Produit ajouté au panier']);
    }

    public function update(Request $request, $cartItemId)
    {
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cartItem->quantity = max(1, intval($request->input('quantity')));
        $cartItem->save();

        return response()->json(['message' => 'Quantité mise à jour']);
    }

    public function remove($cartItemId)
    {
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Article supprimé du panier']);
    }

    public function clear()
    {
        auth()->user()->cartItems()->delete();
        return response()->json(['message' => 'Panier vidé']);
    }
}
