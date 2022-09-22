@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-3">
                <h3>Products</h3>
                @foreach($products as $item)
                    <div class="mt-5">
                        <div class="card">
                            <div>
                                <label for="exampleInputEmail1">{{$item->name}}</label>
                            </div>
                            <div>
                                <label for="amount">{{$item->amount}}</label>
                            </div>
                            <div>
                                <label for="count">{{$item->count}}</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

