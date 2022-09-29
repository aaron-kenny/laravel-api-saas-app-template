@extends('layouts.app')

@section('page-title', 'API Tokens')

@section('content')
<div class="container">
    <div class="row gy-8">

        <div class="col-12">
            <h1 class="h2">API tokens</h1>
        </div>

        @if($tokens->isNotEmpty())
            <!-- TOKENS -->
            <div class="col-12">
                <div class="card card-bordered fade-up">
                    <div class="card-body p-lg-8">
                        <h2 class="card-title h5 mb-0">Personal Access Tokens</h2>
                        <p class="card-subtitle small mb-4">You may have up to 10 tokens for Product Two.</p>
                        @foreach($tokens as $token)
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0"><strong>{{ ucfirst($token->name) }}</strong> @foreach($token->abilities as $ability)<span class="badge">{{ $ability }}</span> @endforeach</p>
                                    <p class="small text-muted mb-md-0">{{ $token->token }}</p>
                                </div>
                                <div class="col-md-auto">
                                    <a class="btn btn-sm btn-primary-outline mr-2" href="{{ route('api.token.edit', $token->id) }}">Edit</a>
                                    <a class="btn btn-sm btn-danger-outline" href="#" onclick="event.preventDefault();document.getElementById('revoke{{ $token->id }}').submit();">Delete</a>
                                    <form id="revoke{{ $token->id }}" class="d-none" action="{{ route('api.token.destroy', $token->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                        <div class="row">
                            <div class="col-md-auto">
                                <a class="btn btn-primary d-block" href="{{ route('api.token.create') }}">Generate New Token</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END TOKENS -->
        @else
            <div class="col-12">
                <div class="border-2 rounded text-center px-6 py-10">
                    <svg class="mh-12 fill-1 mb-6" viewBox="0 0 24 24">
                        <path d="M22,18V22H18V19H15V16H12L9.74,13.74C9.19,13.91 8.61,14 8,14A6,6 0 0,1 2,8A6,6 0 0,1 8,2A6,6 0 0,1 14,8C14,8.61 13.91,9.19 13.74,9.74L22,18M7,5A2,2 0 0,0 5,7A2,2 0 0,0 7,9A2,2 0 0,0 9,7A2,2 0 0,0 7,5Z" />
                    </svg>
                    <h1 class="h5 mb-1">Personal Access Tokens</h1>
                    <p>Personal access tokens function like a combined name and password for API Authentication. Generate a token to access the Product Two API.</p>
                    <a class="btn btn-primary" href="{{ route('api.token.create') }}">Generate New Token</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
