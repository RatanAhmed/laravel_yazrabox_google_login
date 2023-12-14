@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="">
                        <h4>{{ __('Dashboard') }}</h4>
                    </div>
                    <div >
                        <a href="{{ route ('users.index')}}" class="btn btn-sm btn-success">Users</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                    
                    <h4 class="text-center">{{ __('Project Task List') }}</h4>

                    <ol>
                        <li>Laravel 10 setup</li>
                        <li>Social signin - google, username, id</li>
                        <li>5lac user entry using faker</li>
                        <li>Ajax edit and delete using yazrqbox using modal</li>
                        <li>Add and edit using single modal</li>
                        <li>Flush message</li>
                    </ol>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
