@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ $symbol->price }}
                    <h3 id="price">{{ $symbol->price }}</h3>    
                </div>
            </div>
            @if(count($orders) > 0)
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Trade</th>
                        <th scope="col">Entry</th>
                        <th scope="col">Exit</th>
                        <th scope="col">Points</th>
                        <th scope="col">PNL</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $totalPoint = 0;
                    $totalPnl = 0;
                @endphp
                    @foreach($orders as $order)
                        @php
                        $totalPoint += $order->sprice - $order->bprice;
                        $totalPnl += ($order->sprice - $order->bprice) * 20;
                        $isopen = $order->bprice && $order->sprice;
                        $order->bprice = $order->bprice ? $order->bprice : $symbol->price;
                        $order->sprice = $order->sprice ? $order->sprice : $symbol->price;
                        @endphp
                    <tr class="table-info">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td class="text-capitalize font-weight-bold font-italic {{ $order->position == 'long' ?'text-success' : 'text-danger'}}" >{{ $order->position }}</td>
                        @if($order->position == 'long')
                            <td> <span>{{ $order->bprice }}</span> <span class="d-block font-italic small">{{ $order->created_at->format('d M h:i a') }}</span> </td>
                            <td> <span>{{ $order->sprice }}</span> <span class="d-block font-italic small">{{ $isopen ? $order->updated_at->format('d M h:i a'): 'Open' }}</span></td>
                        @else
                            <td> <span>{{ $order->sprice }}</span> <span class="d-block font-italic small">{{ $order->created_at->format('d M h:i a') }}</span></td>
                            <td> <span>{{ $order->bprice }}</span> <span class="d-block font-italic small">{{ $isopen ? $order->updated_at->format('d M h:i a') : 'Open' }}</span> </td>
                        @endif
                        <td>{{ $order->sprice - $order->bprice}}</td>
                        <td>{{ ($order->sprice - $order->bprice) * 20 }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-info">
                        <th scope="row"></th>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td>{{ $totalPoint }}</td>
                        <td>{{ $totalPnl }}</td>
                    </tr>
                </tbody>
            </table>
            @else
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">No Order Today Till Now</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-add"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    
    //let price = document.querySelector('#price');

    setInterval(()=>{
    fetch('\api/symbol/BANKNIFTY')
    .then(response => response.json())
    .then(data => {
        document.querySelector('#price').innerText = data.data.price
        //  price.innerText = 
        });
    },10000);
</script>
@endsection