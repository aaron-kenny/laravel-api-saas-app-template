@extends('layouts.app')

@section('page-title', 'Settings')

@section('content')
    <div class="container">
        <div class="row gy-8">

            <div class="col-12">
                <h1 class="h2">Settings</h1>
            </div>

            <div class="col-12">
                <div class="card card-bordered">
                    <div class="card-body p-lg-8">
                        <h2 class="card-title h4 border-bottom-2 pb-3 mb-3">Notifications</h2>
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <div class="col-xl-2">
                                    <h3 class="h5 mb-xl-0">Broker Response</h3>
                                </div>
                                <div class="col-xl-10">
                                    <div class="form-check">
                                        <input id="brokerResponseEmail" class="form-check-input" type="checkbox" name="settings[notifications][brokerResponse][email]" @if($settings['notifications']['brokerResponse']['email']) checked @endif>
                                        <label class="form-check-label" for="brokerResponseEmail">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-auto">
                                    <button class="btn btn-primary w-100 mb-3 mb-md-0">Save settings</button>
                                </div>
                                <div class="col-md-auto">
                                    <a class="btn btn-danger-outline w-100" href="#" onclick="event.preventDefault();document.getElementById('resetDefaults').submit();">Reset defaults</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <form id="resetDefaults" action="{{ route('settings.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
@endsection
