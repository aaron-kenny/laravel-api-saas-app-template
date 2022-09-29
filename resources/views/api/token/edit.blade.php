@extends('layouts.app')

@section('page-title', 'Edit your personal access token')

@section('content')
<div class="container">
    <h1 class="page__title">API tokens</h1>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title mb-0">Edit personal access token</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('api.token.update', $token->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <h3>Token name</h3>
                    <input class="form-control @error('token_name') is-invalid @enderror" type="text" name="token_name" value="{{ $token->name }}" placeholder="e.g. TradingView">
                    @error('token_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <h3>Select permissions</h3>
                    <div class="custom-control custom-control-inline custom-checkbox">
                        <input id="read" class="custom-control-input" type="checkbox" name="read" disabled checked>
                        <label class="custom-control-label" for="read">Read (default)</label>
                    </div>
                    <div class="custom-control custom-control-inline custom-checkbox">
                        <input id="write" class="custom-control-input" type="checkbox" name="write_permission" @if($has_write_permission) checked @endif>
                        <label class="custom-control-label" for="write">Write</label>
                    </div>
                </div>
                <button class="btn btn-primary">Update Token</button>
            </form>
        </div>
    </div>
</div>
@endsection
