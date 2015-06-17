@extends('layouts.default')

@section('title')
    Error - @parent
@endsection

@section('body-class', 'with-centered-container');

@section('content')
    <div class="centered-container">
        <div class="centered-content">
            <div class="jumbotron">
                <h1>{{ $status }} {{ $phrase }}</h1>
                <p>
                    @if (Lang::has("errors.http_{$status}"))
                        {{ trans("errors.http_{$status}") }}
                    @else
                        {{ trans("errors.http_default") }}
                    @endif
                </p>
            </div>
         </div>
    </div>
@endsection
