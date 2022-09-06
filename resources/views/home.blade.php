@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-left">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1>{{$user->name}}</h1>
                    <h6>{{$user->email}}</h6>
                </div>
                <div class="d-flex justify-content-end">
{{--                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">--}}
{{--                        +Add Product--}}
{{--                    </button>--}}
                </div>

                <!-- Modal -->
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Product</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <form action="{{ url('insertProduct') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Product Name</label>
                                        <input type="text" class="form-control" id="name"  name="name" placeholder="Product name">
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" step="0.01" class="form-control"  name="amount" id="amount" placeholder="Amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="count">Count</label>
                                        <input type="number" class="form-control"  name="count" id="amount" placeholder="Count">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Add</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
<script>
    function myFunction(){
        alert(1)
    }
</script>
