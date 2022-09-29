@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="row gy-8">
            <div class="col-12">
                <h1 class="h2">Dashboard</h1>
            </div>
            <div class="col-12">
                <div class="border-2 rounded text-center px-6 py-10">
                    <img class="mh-12 fill-1 mb-6" src="INSERT_PATH_TO_PRODUCT_LOGO">
                    <h2 class="h5 mb-6">Welcome to Product Two</h2>
                    <a class="btn btn-primary" href="{{ config('app.docs_url') }}">Read the documentation</a>
                </div>
            </div>
            <div class="col-12">
                <div class="card card-bordered fade-up">
                    <div class="card-body p-lg-8">
                        <h2 class="card-title mb-0">Webhook URL</h2>
                        <p class="mb-0">https://product-two.laravelwebsite.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
