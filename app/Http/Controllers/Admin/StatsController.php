<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        // ✅ Chiffre d'affaires total : commandes payées
        $totalRevenue = Order::where('payment_status', 'payé')->sum('total_amount');

        // ✅ Nombre total de commandes
        $totalOrders = Order::count();

        // ✅ Quantité totale vendue
        $totalProductsSold = OrderItem::sum('quantity');

        // ✅ Nombre total d'utilisateurs
        $totalUsers = User::count();

        // ✅ Meilleurs produits (quantité vendue)
        $topProducts = Product::withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // ✅ Statistiques du mois en cours
        $startOfMonth = Carbon::now()->startOfMonth();

        $monthlyRevenue = Order::where('payment_status', 'payé')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $monthlyOrders = Order::where('created_at', '>=', $startOfMonth)->count();

        return view('admin.stats.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalProductsSold',
            'totalUsers',
            'topProducts',
            'monthlyRevenue',
            'monthlyOrders'
        ));
    }
}
