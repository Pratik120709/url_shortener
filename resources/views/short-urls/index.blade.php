@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Short URLs</span>
                    @can('create_short_url')
                    <a href="{{ route('short-urls.create') }}" class="btn btn-primary btn-sm">
                        Create New
                    </a>
                    @endcan

                <a href="{{ route('short-urls.export') }}" class="btn btn-sm btn-info">
                    Download
                </a>
                </div>

                <div class="card-body">
                    @if($shortUrls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Short Code</th>
                                        <th>Original URL</th>
                                        <th>Clicks</th>
                                        <th>Created By</th>
                                        @if(Auth::user()->isSuperAdmin())
                                            <th>Company</th>
                                        @endif
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shortUrls as $url)
                                        <tr>
                                            <td>
                                                <a href="{{ $url->short_url }}" target="_blank">
                                                    {{ $url->short_code }}
                                                </a>
                                            </td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $url->original_url }}
                                            </td>
                                            <td>{{ $url->clicks }}</td>
                                            <td>{{ $url->user->name }}</td>
                                            @if(Auth::user()->isSuperAdmin())
                                                <td>{{ $url->company->name }}</td>
                                            @endif
                                            <td>{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('short-urls.show', $url) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                                @if(Auth::user()->id == $url->user_id || (Auth::user()->isAdmin() && Auth::user()->company_id == $url->company_id))
                                                    <form action="{{ route('short-urls.destroy', $url) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $shortUrls->links() }}
                    @else
                        <p class="text-center">No short URLs found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
