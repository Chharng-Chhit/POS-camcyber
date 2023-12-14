<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function getDashboardInfo()
    {
        $totalSaleToday = Order::sum('total_price');

        $data = [
            'total_sale_today' => $totalSaleToday
        ];
        return response()->json($data, Response::HTTP_OK);
    }
}