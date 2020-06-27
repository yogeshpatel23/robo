<?php

namespace App\Http\Controllers;

use App\Strategy;
use App\Order;
use App\Symbol;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::where('user_id',auth()->id())->whereDate('updated_at',today())->orderBy('id','desc')->get();
        $symbol = Symbol::where('name', 'BANKNIFTY' )->firstOrFail();
        return view('home',compact('orders','symbol'));
    }

    public function setStrategy()
    {
        // dd(request()->input('strategy'));
        auth()->user()->strategies()->detach();
        foreach(request()->input('strategy') as $strategy){
            auth()->user()->strategies()->attach(Strategy::find($strategy));
        }

        return redirect()->back();
        //dd(auth()->user());
    }

    public function strategies()
    {
       $strategies = Strategy::all();
       return view('strategies',compact('strategies'));
    }
}
