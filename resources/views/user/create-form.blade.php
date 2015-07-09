<form role="form" class="form-horizontal" method="POST" action="{{ url('user') }}">
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
            <input class="form-control" type="text" name="name"
                   data-on-blur="validateFormField($('#modal-form [name=name]'), '{{ url('user/validate-create-form') }}')"
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
            <input class="form-control" type="text" name="email"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('user/validate-create-form') }}')"
                   data-on-enter="$('#modal-form [name=password]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password1">
            {{ trans('user.password1_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('user/validate-create-form') }}')"
                   data-on-enter="$('#modal-form [name=password_confirmation]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_confirmation">
            {{ trans('user.password2_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
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
                    <input type="checkbox" name="is_active" value="1" checked="checked"
                           data-on-blur="validateFormField($('#modal-form [name=is_active]'), '{{ url('user/validate-create-form') }}')"
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
                    <input type="checkbox" name="is_admin" value="1"
                           data-on-blur="validateFormField($('#modal-form [name=is_admin]'), '{{ url('user/validate-create-form') }}')"
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
        validateFormField($('#modal-form [name=password]'), '{{ url('user/validate-create-form') }}');
        validateFormField($('#modal-form [name=password_confirmation]'), '{{ url('user/validate-create-form') }}');
    }

    var modal = $('#modal-form');

    modal.find('.modal-title').text("{{ trans('user.form_title') }}");
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('user.form_submit') }}");
    modal.find('.modal-footer .footer-text').hide();

    runModalForm(modal);
</script>
