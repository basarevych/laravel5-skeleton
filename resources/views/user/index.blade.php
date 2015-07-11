@extends('layouts.default')

@section('title')
    {{ trans('user.page_title') }} - @parent
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ trans('user.page_title') }}</h1>
                <button class="btn btn-default" onclick="openModalForm('{{ url('user/create') }}')">
                    {{ trans('user.create_button') }}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
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
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ trans('messages.' . ($user->is_active ? 'yes' : 'no')) }}</td>
                                <td>{{ trans('messages.' . ($user->is_admin ? 'yes' : 'no')) }}</td>
                                <td class="buttons-column">
                                    <button class="btn btn-xs btn-default" onclick="openModalForm('{{ url('user/' . $user->id . '/edit') }}')">
                                        {{ trans('user.edit_button') }}
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="deleteUser({{ $user->id }}, '{{ trans('user.delete_confirm', [ 'id' => $user->id, 'email' => $user->email ]) }}')">
                                        {{ trans('user.delete_button') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <td colspan="6">
                            <div class="pagination btn-group pull-right" role="group">
                                <a href="javascript:setSize(15)"
                                    class="btn btn-{{ $size == 15 ? 'primary' : 'default' }}">15</a>
                                <a href="javascript:setSize(30)"
                                    class="btn btn-{{ $size == 30 ? 'primary' : 'default' }}">30</a>
                                <a href="javascript:setSize(50)"
                                    class="btn btn-{{ $size == 50 ? 'primary' : 'default' }}">50</a>
                                <a href="javascript:setSize(100)"
                                    class="btn btn-{{ $size == 100 ? 'primary' : 'default' }}">100</a>
                                <a href="javascript:setSize(0)"
                                    class="btn btn-{{ $size == 0 ? 'primary' : 'default' }}">{{ trans('messages.all') }}</a>
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

        function deleteUser(id, question) {
            var url = '{{ url('user') }}' + '/' + id;
            bsConfirm(
                question,
                '{{ trans('user.delete_title') }}',
                '{{ trans('user.delete_button') }}',
                function () {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            $.ajax({
                                url: url,
                                method: 'DELETE',
                                data: {
                                    '_token': data._token,
                                },
                                success: function () {
                                    $('#modal-form').modal('hide');
                                    window.location.reload();
                                },
                            });
                        },
                    });
                }
            );
        }
    </script>
@endsection
