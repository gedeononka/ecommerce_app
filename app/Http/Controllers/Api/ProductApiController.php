<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(12));
    }

    public function show(Product $product)
    {
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)->get();

        return response()->json([
            'product' => $product,
            'related' => $related
        ]);
    }
}
