@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="/setStrategy" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="strategy">Select Strategy</label>
                            <select id="strategy" name="strategy[]" class="custom-select" multiple>
                            <option selected>None</option>
                            @foreach($strategies as $strategy)
                            <option value="{{ $strategy->id }}">{{ $strategy->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" >Save</button>
                    </form>   
                </div>
            </div>            
        </div>
    </div>
</div>
@endsection