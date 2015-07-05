@extends('layouts.default')

@section('title')
    {{ trans('user.page_title') }} - @parent
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-3 col-sm-9">
                <h1>{{ trans('user.page_title') }}</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div data-sidebar="sm">
                    <div id="actions-panel" class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('user.actions_title') }}</h3>
                        </div>
                        <div class="panel-body button-panel">
                            <button id="create-button" class="btn btn-primary disabled">
                                {{ trans('user.create_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
            <div>
        </div>
    </div>
@endsection
