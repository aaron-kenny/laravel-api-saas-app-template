@extends('layouts.app')

@section('page-title', 'Create a Personal Access Token')

@section('content')
    <div class="container">
        <div class="row gy-8">

            <div class="col-12">
                <h1 class="h2">New personal access token</h1>
            </div>

            <div class="col-12">
                <div class="card card-bordered">
                    <div class="card-body p-lg-8">
                        <form action="{{ route('api.token.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <h3 class="h5">Token name</h3>
                                <input class="form-control @error('token_name') is-invalid @enderror" type="text" name="token_name" value="{{ old('token_name') }}" placeholder="e.g. TradingView">
                                @error('token_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <h3 class="h5">Select permissions</h3>

                                <div class="form-check">
                                    <input id="read" class="form-check-input" type="checkbox" name="read" disabled checked>
                                    <label class="form-check-label" for="read">Read (default)</label>
                                </div>
                                <div class="form-check">
                                    <input id="write" class="form-check-input" type="checkbox" name="write_permission" checked>
                                    <label class="form-check-label" for="write">Write</label>
                                </div>
                            </div>
                            <button class="btn btn-primary">Generate Token</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
