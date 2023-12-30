<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // TODO: Completed this method
        $query = Order::whereBetween('created_at', [$request->from, $request->to])->get();
       
        $noAffiliate = Order::where('affiliate_id', null)->first();

        $payload = [
            'count' => $query->count(),
            'commissions_owed' => $query->sum('commission_owed') - $noAffiliate->commission_owed,
            'revenue' => $query->sum('subtotal'),
        ];

        return response()->json($payload);
    }
}
