@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('Dashboard') }}</div>
            <div class="card-body">
                <h5>Welcome, {{ Auth::user()->name }}!</h5>
                <p>Role: <strong>{{ Auth::user()->getRoleNames()->first() }}</strong></p>

                @if(Auth::user()->company)
                    <p>Company: <strong>{{ Auth::user()->company->name }}</strong></p>
                @endif

                @if(Auth::user()->can('view_all_short_urls') || Auth::user()->can('view_company_short_urls') || Auth::user()->can('view_own_short_urls'))
                    <div class="mt-4">
                        <a href="{{ route('short-urls.index') }}" class="btn btn-primary">
                            View Short URLs
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
