@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Create Short URL</div>
            <div class="card-body">
                <form method="POST" action="{{ route('short-urls.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="original_url" class="form-label">Original URL *</label>
                        <input type="url" class="form-control @error('original_url') is-invalid @enderror"
                               id="original_url"  placeholder="Original URL" name="original_url"
                               value="{{ old('original_url') }}" required>
                        @error('original_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="custom_code" class="form-label">Custom Short Code (Optional)</label>
                        <input type="text" class="form-control @error('custom_code') is-invalid @enderror"
                               id="custom_code" placeholder="Custom Short Code" name="custom_code"
                               value="{{ old('custom_code') }}">
                        @error('custom_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave blank for auto-generated code</small>
                    </div>

                    <div class="mb-3">
                        <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                        <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                               id="expires_at" name="expires_at"
                               value="{{ old('expires_at') }}">
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Create Short URL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
