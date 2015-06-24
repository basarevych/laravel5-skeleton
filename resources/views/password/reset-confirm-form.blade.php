<form role="form" class="form-horizontal" method="POST" action="{{ url('auth/reset-confirm-form') }}">
    {!! csrf_field() !!}
    <input type="hidden" name="reset_token" value="{{ $token }}">

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password1">
            {{ trans('password.password1_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('auth/validate-confirm-form') }}');"
                   data-on-enter="$('#modal-form [name=password_confirmation]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_confirmation">
            {{ trans('password.password2_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password_confirmation"
                   data-on-blur="validatePasswords()"
                   data-on-enter="$('#modal-form [type=submit]').focus().click()">
            <div class="help-block"></div>
        </div>
    </div>
</form>

<script>
    function validatePasswords() {
        validateFormField($('#modal-form [name=password]'), '{{ url('auth/validate-confirm-form') }}');
        validateFormField($('#modal-form [name=password_confirmation]'), '{{ url('auth/validate-confirm-form') }}');
    }

    var modal = $('#modal-form');

    modal.find('.modal-title').text("{{ trans('password.confirm_title') }}");
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('password.confirm_submit') }}");

    runModalForm(modal);
</script>
