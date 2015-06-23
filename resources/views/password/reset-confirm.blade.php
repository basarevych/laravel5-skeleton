@extends('layouts.default')

@section('title')
    Reset password - @parent
@endsection

@section('content')
    <script>
        openModalForm('{{ url('auth/reset-confirm-form/' . $token) }}');
    </script>
@endsection
