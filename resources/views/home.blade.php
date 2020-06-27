@extends('layouts.user')

@section('content')
<div class="container">
   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    BANKNIFTY
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
                        <th class="text-right" scope="col">Points</th>
                        <th class="text-right" scope="col">PNL</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $totalPoint = 0;
                    $totalPnl = 0;
                @endphp
                    @foreach($orders as $order)
                        @php
                        $isopen = $order->bprice && $order->sprice;
                        $order->bprice = $order->bprice ? $order->bprice : $symbol->price;
                        $order->sprice = $order->sprice ? $order->sprice : $symbol->price;
                        $point = $order->sprice - $order->bprice;
                        $totalPoint += $point;
                        @endphp
                    <tr class="{{ $isopen ? 'table-info' : 'table-success'}}">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td class="text-capitalize font-weight-bold font-italic {{ $order->position == 'long' ?'text-success' : 'text-danger'}}" >{{ $order->position }}</td>
                        @if($order->position == 'long')
                            <td> <span class="bprice">{{ $order->bprice }}</span> <span class="d-block font-italic small">{{ $order->created_at->format('d M h:i a') }}</span> </td>
                            <td> <span class="sprice cprice">{{ $order->sprice }}</span> <span class="d-block font-italic small">{{ $isopen ? $order->updated_at->format('d M h:i a'): 'Open' }}</span></td>
                        @else
                            <td> <span class="sprice">{{ $order->sprice }}</span> <span class="d-block font-italic small">{{ $order->created_at->format('d M h:i a') }}</span></td>
                            <td> <span class="bprice cprice">{{ $order->bprice }}</span> <span class="d-block font-italic small">{{ $isopen ? $order->updated_at->format('d M h:i a') : 'Open' }}</span> </td>
                        @endif
                        <td class="text-right cpoint {{ $point < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($point,2)}}</td>
                        <td class="text-right cpnl {{ $point < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($point * 20 ,2)}}</td>
                    </tr>
                    @endforeach
                    <tr class="table-info">
                        <th scope="row"></th>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td class="text-right font-weight-bold tpoint">{{ number_format($totalPoint,2) }}</td>
                        <td class="text-right font-weight-bold tpnl">{{ number_format($totalPoint * 20,2) }}</td>
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
    
   let active = document.querySelector('tr.table-success');
   let bprice = document.querySelector('tr.table-success .bprice').innerText;
   let sprice = document.querySelector('tr.table-success .sprice').innerText;
   let tpoint = document.querySelector('tr .tpoint').innerText;
    
   let cpoint = parseFloat(sprice) - parseFloat(bprice);
   let point = (parseFloat(tpoint) - cpoint ).toFixed(2);

    setInterval(()=>{
    fetch('/api/symbol/BANKNIFTY')
    .then(response => response.json())
    .then(data => {
        let price = data.data.price;
        document.querySelector('#price').innerText = price;
        document.querySelector('tr.table-success .cprice').innerHTML = price;


        let bprice = document.querySelector('tr.table-success .bprice').innerText;
        let sprice = document.querySelector('tr.table-success .sprice').innerText;
        
        let newPoint = parseFloat(sprice) - parseFloat(bprice);
        let cpointspan = document.querySelector('tr.table-success .cpoint');
        let cpnlspan = document.querySelector('tr.table-success .cpnl')
        cpointspan.innerText = newPoint.toFixed(2);
        cpnlspan.innerText = (newPoint * 20).toFixed(2);
        if(newPoint < 0) {
            cpointspan.classList.remove('text-success');
            cpnlspan.classList.remove('text-success');
            cpointspan.classList.add('text-danger');
            cpnlspan.classList.add('text-danger');
        } else {
            cpointspan.classList.add('text-success');
            cpnlspan.classList.add('text-success');
            cpointspan.classList.remove('text-danger');
            cpnlspan.classList.remove('text-danger');
        }
        let newTotalpoint = parseFloat(point) + newPoint;
        document.querySelector('tr .tpoint').innerText = newTotalpoint.toFixed(2);
        document.querySelector('tr .tpnl').innerText = (newTotalpoint*20).toFixed(2);
        console.log(newTotalpoint);
        });
    },10000);
</script>
@endsection