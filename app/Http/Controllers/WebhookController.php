<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Completed this method
        $this->orderService->processOrder($request->all());

        return response()->json([], 200);
    }
}
