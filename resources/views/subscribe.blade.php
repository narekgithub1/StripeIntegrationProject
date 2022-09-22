@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-3">
                <h3>Plans</h3>
                @foreach($plans as $item)
                    <div class="mt-5">
                        <div class="card">
                        <form action="/subscribe" method="post">
                            {{csrf_field()}}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Name"><span style="font-size: 30px">Plan Name : <b>{{$item['name']}}</b></span></label>
                                        <input hidden type="text" class="form-control"  value="{{$item['name']}}" name="name">
                                    </div>
                                    <div>
                                        <label for="Amount"><span style="font-size: 25px"> Amount : <b>{{$item['amount_decimal']/100}}</b></span></label>
                                        <input hidden type="text" class="form-control"  value="{{$item['amount_decimal']}}" name="amount">
                                    </div>
                                    <div>
                                        <label for="Currency"><span style="font-size: 25px"> Currency : <b>{{$item['currency']}}</b></span></label>
                                        <input hidden type="text" class="form-control"  value="{{$item['currency']}}" name="currency">
                                    </div>
                                    <div>
                                        <label for="Interval"><span style="font-size: 20px"> Interval : <b>{{$item['interval']}}</b></span></label>
                                        <input hidden type="text" class="form-control"  value="{{$item['interval']}}" name="interval">
                                    </div>
                                    <input hidden type="text" class="form-control"  value="{{$item['id']}}" name="plan_id">
                                    <input hidden type="text" class="form-control"  value="{{auth()->user()->email }}" name="email">

                                </div>
                            <div class="d-flex justify-content-end align-items-center p-2">
                                <div>
                                    <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="pk_test_51K8gGfJdvGvyuwvzhKNr6J4XHxf9TGM5tpk89INc6x3VIcm2AJfjKB9s7724GKfh3yK0vSUurFHN87Q97mc16A0m00DjBiWPXF"
                                        data-amount="{{$item['amount_decimal']}}"
                                        data-label="Subscribe now"
                                        data-name="Demo Site"
                                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                        data-description="Widget"
                                        data-locale="auto"
                                    >
                                    </script>
                                </div>
                                @if($item['id'] === $planStripeName && $planStatus === 'active')
                                <button type="button" class="btn btn-danger ml-2"
                                        onclick="cancelSubscription(`{{$item['id']}}`)">Cancel Subscription
                                </button>
                                @endif
                            </div>
                        </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script>
            function  cancelSubscription(item_id){
              axios.post('/api/cancel-subscription', { id : item_id});
            }
        </script>
    </div>

@endsection

