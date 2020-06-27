<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as ResourcesOrder;
use App\Symbol;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $symbol = Symbol::paginate(20);
        return ResourcesOrder::collection($symbol);
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
        $symbol = Symbol::updateOrCreate(
            ['name' => $request->input('name')],
            ['price' => $request->input('price')]
        );

        return new ResourcesOrder($symbol);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Symbol  $symbol
     * @return \Illuminate\Http\Response
     */
    public function show($symbol)
    {
        //
        $symbolData = Symbol::where('name', $symbol )->firstOrFail();
        return new ResourcesOrder($symbolData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Symbol  $symbol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Symbol $symbol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Symbol  $symbol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Symbol $symbol)
    {
        //
    }
}
