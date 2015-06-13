@extends('layouts.default')

@section('title')
    Welcome - @parent
@endsection

@section('head')
    @parent

    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0 !important;
            width: 100%;
            height: 100%;
            display: table;
        }

        .centered-container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .centered-content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-family: 'Lato';
            font-size: 96px;
            margin-bottom: 40px;
            color: #B0BEC5;
        }

        .quote {
            font-size: 24px;
            color: #B0BEC5;
        }
    </style>
@endsection

@section('content')
    <div class="centered-container">
        <div class="centered-content">
            <div class="title">Laravel 5</div>
            <div class="quote">{{ Inspiring::quote() }}</div>
        </div>
    </div>
@endsection
