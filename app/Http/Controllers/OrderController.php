<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as ResourcesOrder;
use App\Order;
use App\Strategy;
use Illuminate\Http\Request;

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
        $orders = Order::paginate(20);

        return ResourcesOrder::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $users = Strategy::where('name', 'Renko-Trend')->first()->users()->get();
        foreach ($users as $user) {
            // dd($user->id);

            if ($request->input('orderType') == 'buy') {
                $order = Order::firstWhere(['user_id' => $user->id, 'symbol' => $request->input('symbol'), 'position' => 'short', 'bprice' => null]);
                if ($order) {
                    $order->bprice = $request->input('price');
                    $order->save();
                    Order::create([
                        'user_id' => $user->id,
                        'symbol' => $request->input('symbol'),
                        'position' => 'long',
                        'bprice' => $request->input('price')
                    ]);
                } else {
                    Order::create([
                        'user_id' => $user->id,
                        'symbol' => $request->input('symbol'),
                        'position' => 'long',
                        'bprice' => $request->input('price')
                    ]);
                }
                // return new ResourcesOrder($order);
            }

            if ($request->input('orderType') == 'sell') {
                $order = Order::firstWhere(['user_id' => $user->id, 'symbol' => $request->input('symbol'), 'position' => 'long', 'sprice' => null]);
                if ($order) {
                    $order->sprice = $request->input('price');
                    $order->save();
                    Order::create([
                        'user_id' => $user->id,
                        'symbol' => $request->input('symbol'),
                        'position' => 'short',
                        'sprice' => $request->input('price')
                    ]);
                } else {
                    Order::create([
                        'user_id' => $user->id,
                        'symbol' => $request->input('symbol'),
                        'position' => 'short',
                        'sprice' => $request->input('price')
                    ]);
                }
                // return new ResourcesOrder($order);
            }

            if ($request->input('orderType') == 'close') {
                $border = Order::firstWhere(['user_id' => $user->id, 'symbol' => $request->input('symbol'), 'position' => 'long', 'sprice' => null]);
                if ($border) {
                    $border->sprice = $request->input('price');
                    $border->save();
                }

                $sorder = Order::firstWhere(['user_id' => $user->id, 'symbol' => $request->input('symbol'), 'position' => 'short', 'bprice' => null]);
                if ($sorder) {
                    $sorder->bprice = $request->input('price');
                    $sorder->save();
                }
                // return new ResourcesOrder($order);
            }   
        }
        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
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
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
