@extends('layouts.app')
@section('content')
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <button
                            class="btn btn-outline-secondary"
                            data-toggle="modal"
                            data-target="#myModal"
                            data-user_id="{{$user->id}}"
                        >
                            Open chat
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('modals.chat')
    </div>
@endsection
