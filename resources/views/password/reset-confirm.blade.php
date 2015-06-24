@extends('layouts.default')

@section('title')
    {{ trans('password.confirm_title') }} - @parent
@endsection

@section('content')
    <script>
        @if ($expired)
            bsAlert("{{ trans('password.invalid_token') }}", "{{ trans('password.confirm_title') }}");
        @else
            openModalForm('{{ url('auth/reset-confirm-form/' . $token) }}');
        @endif
    </script>
@endsection
