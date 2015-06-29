@extends('layouts.default')

@section('title')
    {{ trans('messages.app_title') }} - @parent
@endsection

@section('content')
    <script>
        @if ($expired)
            bsAlert("{{ trans('registration.expired') }}", "{{ trans('registration.form_title') }}");
        @else
            bsAlert(
                "{{ trans('registration.welcome') }}",
                "{{ trans('registration.form_title') }}",
                function () { window.location = "{{ url('/') }}"; }
            );
        @endif
    </script>
@endsection
