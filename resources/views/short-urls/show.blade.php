@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Short URL Details</div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Short Code:</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $shortUrl->short_url }}" target="_blank" class="text-decoration-none">
                            {{ $shortUrl->short_code }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </dd>

                    <dt class="col-sm-3">Original URL:</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $shortUrl->original_url }}" target="_blank" class="text-truncate d-block" style="max-width: 400px;">
                            {{ $shortUrl->original_url }}
                        </a>
                    </dd>

                    <dt class="col-sm-3">Short URL:</dt>
                    <dd class="col-sm-9">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" value="{{ $shortUrl->short_url }}" readonly id="shortUrlInput">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </dd>

                    <dt class="col-sm-3">Total Clicks:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-primary">{{ $shortUrl->clicks }}</span>
                    </dd>

                    <dt class="col-sm-3">Created By:</dt>
                    <dd class="col-sm-9">{{ $shortUrl->user->name }}</dd>

                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <dt class="col-sm-3">Company:</dt>
                    <dd class="col-sm-9">{{ $shortUrl->company->name }}</dd>
                    @endif

                    <dt class="col-sm-3">Created At:</dt>
                    <dd class="col-sm-9">{{ $shortUrl->created_at->format('Y-m-d H:i:s') }}</dd>

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        @if($shortUrl->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </dd>

                    @if($shortUrl->expires_at)
                    <dt class="col-sm-3">Expires At:</dt>
                    <dd class="col-sm-9">
                        {{ $shortUrl->expires_at->format('Y-m-d H:i:s') }}
                        @if($shortUrl->isExpired())
                            <span class="badge bg-warning ms-2">Expired</span>
                        @endif
                    </dd>
                    @endif

                    @if($shortUrl->updated_at != $shortUrl->created_at)
                    <dt class="col-sm-3">Last Updated:</dt>
                    <dd class="col-sm-9">{{ $shortUrl->updated_at->format('Y-m-d H:i:s') }}</dd>
                    @endif
                </dl>

                <div class="mt-4">
                    <a href="{{ route('short-urls.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>

                    @if(Auth::user()->id == $shortUrl->user_id || (Auth::user()->isAdmin() && Auth::user()->company_id == $shortUrl->company_id))
                        <form action="{{ route('short-urls.destroy', $shortUrl) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this short URL?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('shortUrlInput');
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(input.value).then(() => {
        // Show feedback
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('Failed to copy to clipboard');
    });
}
</script>

<style>
    dt {
        font-weight: 600;
    }
    dd {
        margin-bottom: 0.5rem;
    }
</style>
@endsection
