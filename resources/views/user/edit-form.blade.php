<form role="form" class="form-horizontal" method="POST" action="{{ url('user/' . $user->id) }}">
    <input type="hidden" name="_method" value="PUT">
    {!! csrf_field() !!}

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">
            {{ trans('user.name_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}"
                   data-on-blur="validateFormField($('#modal-form [name=name]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}')"
                   data-on-enter="$('#modal-form [name=email]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">
            {{ trans('user.email_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" value="{{ old('email', $user->email) }}"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}')"
                   data-on-enter="$('#modal-form [name=password]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password1">
            {{ trans('user.password1_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}')"
                   data-on-enter="$('#modal-form [name=password_confirmation]').focus()">
            <p class="help-block">{{ trans('user.password_notice') }}</p>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_confirmation">
            {{ trans('user.password2_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password_confirmation"
                   data-on-blur="validatePasswords()"
                   data-on-enter="$('#modal-form [name=is_active]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked="checked"' : '' }}
                           data-on-blur="validateFormField($('#modal-form [name=is_active]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}')"
                           data-on-enter="$('#modal-form [name=is_admin]').focus()">
                    {{ trans('user.is_active_label') }}
                </label>
            </div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked="checked"' : '' }}
                           data-on-blur="validateFormField($('#modal-form [name=is_admin]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}')"
                           data-on-enter="$('#modal-form [type=submit]').focus().click()">
                    {{ trans('user.is_admin_label') }}
                </label>
            </div>
            <div class="help-block"></div>
        </div>
    </div>
</form>

<script>
    function validatePasswords() {
        validateFormField($('#modal-form [name=password]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}');
        validateFormField($('#modal-form [name=password_confirmation]'), '{{ url('user/' . $user->id . '/validate-edit-form') }}');
    }

    function deleteUser() {
        bsConfirm(
            '{{ trans('user.delete_confirm', [ 'id' => $user->id, 'email' => $user->email ]) }}',
            '{{ trans('user.delete_title') }}',
            '{{ trans('user.delete_button') }}',
            function () {
                modal.find('.modal-footer .spinner').show();
                modal.find('.modal-footer .buttons').hide();
                $.ajax({
                    url: '{{ url('user/' . $user->id) }}',
                    method: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function () {
                        window.location.reload();
                    },
                });
            },
            function () {
                openModalForm('{{ url('user/' . $user->id . '/edit') }}');
            }
        );
    }

    var modal = $('#modal-form');
    var deleteButton = $('<button type="button" class="btn btn-danger"></button>');
    deleteButton.text('{{ trans('user.delete_button') }}');
    deleteButton.on('click', deleteUser);

    modal.find('.modal-title').text("{{ trans('user.edit_title') }}");
    modal.find('.modal-footer .footer-text').show().empty().append(deleteButton);
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('user.edit_submit') }}");

    runModalForm(modal);
</script>
