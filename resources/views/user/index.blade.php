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
                <table class="table table-striped table-hover">
                    <thead>
                        <th>
                            <a href="javascript:setSort('id')">{{ trans('user.id_label') }}</a>
                            @if ($sortBy == 'id')
                                <span class="glyphicon glyphicon-sort-by-attributes{{ $sortOrder == 'asc' ? '' : '-alt' }}"></span>
                            @endif
                        </th>
                        <th>
                            <a href="javascript:setSort('name')">{{ trans('user.name_label') }}</a>
                            @if ($sortBy == 'name')
                                <span class="glyphicon glyphicon-sort-by-attributes{{ $sortOrder == 'asc' ? '' : '-alt' }}"></span>
                            @endif
                        </th>
                        <th>
                            <a href="javascript:setSort('email')">{{ trans('user.email_label') }}</a>
                            @if ($sortBy == 'email')
                                <span class="glyphicon glyphicon-sort-by-attributes{{ $sortOrder == 'asc' ? '' : '-alt' }}"></span>
                            @endif
                        </th>
                        <th>
                            <a href="javascript:setSort('is_active')">{{ trans('user.is_active_label') }}</a>
                            @if ($sortBy == 'is_active')
                                <span class="glyphicon glyphicon-sort-by-attributes{{ $sortOrder == 'asc' ? '' : '-alt' }}"></span>
                            @endif
                        </th>
                        <th>
                            <a href="javascript:setSort('is_admin')">{{ trans('user.is_admin_label') }}</a>
                            @if ($sortBy == 'is_admin')
                                <span class="glyphicon glyphicon-sort-by-attributes{{ $sortOrder == 'asc' ? '' : '-alt' }}"></span>
                            @endif
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ trans('messages.' . ($user->is_active ? 'yes' : 'no')) }}</td>
                                <td>{{ trans('messages.' . ($user->is_admin ? 'yes' : 'no')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <td colspan="5">
                            <div class="pagination btn-group pull-right" role="group">
                                <a href="javascript:setSize(15)"
                                    class="btn btn-default {{ $size == 15 ? 'active' : '' }}">15</a>
                                <a href="javascript:setSize(30)"
                                    class="btn btn-default  {{ $size == 30 ? 'active' : '' }}">30</a>
                                <a href="javascript:setSize(50)"
                                    class="btn btn-default {{ $size == 50 ? 'active' : '' }}">50</a>
                                <a href="javascript:setSize(0)"
                                    class="btn btn-default {{ $size == 0 ? 'active' : '' }}">{{ trans('messages.all') }}</a>
                            </div>
                            {!! $users->render() !!}
                        </td>
                    </tfoot>
                </table>
            <div>
        </div>
    </div>

    <script>
        var page = {{ $page }},
            size = {{ $size }},
            sortBy = '{{ $sortBy }}',
            sortOrder = '{{ $sortOrder }}';

        function setSort(column) {
            var newOrder = 'asc';
            if (sortBy == column)
                newOrder = (sortOrder == 'asc' ? 'desc' : 'asc');
            window.location = "{{ url('user') }}"
                + '?page=' + page
                + '&size=' + size
                + '&sort_by=' + column
                + '&sort_order=' + newOrder;
        }

        function setSize(size) {
            window.location = "{{ url('user') }}"
                + '?page=1'
                + '&size=' + size
                + '&sort_by=' + sortBy
                + '&sort_order=' + sortOrder;
        }
    </script>
@endsection
