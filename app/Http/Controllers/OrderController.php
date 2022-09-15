<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->has_min)
            if ($product->min > $request->count)
                return response(['message' => 'مقدار خرید از حد مجاز برای این محصول کمتر است'], 500);


        if ($product->has_max)
            if ($product->max < $request->count)
                return response(['message' => 'مقدار خرید از حد مجاز برای این محصول بیشتر است'], 500);

        $order = Order::select(DB::raw('sum(count) as sumb'))->where('user_id', $request->user_id)->where('created_at', '>', now()->addDays(-30))->first();

        if (($order->sumb + $request->count) > $product->max_count_per_month)
            return response(['message' => 'تعداد خرید ماهانه از حد مجاز برای این محصول بیشتر است'], 500);




        $order = Order::create($request->all());
        return response(['message' => 'خرید موفق بود', 'order' => $order], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
