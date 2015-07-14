<form role="form" class="form-horizontal" method="POST" action="{{ url('auth/registration-form') }}">
    {!! csrf_field() !!}

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">
            {{ trans('registration.name_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                   data-on-blur="validateFormField($('#modal-form [name=name]'), '{{ url('auth/validate-registration-form') }}')"
                   data-on-enter="$('#modal-form [name=email]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">
            {{ trans('registration.email_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" value="{{ old('email') }}"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('auth/validate-registration-form') }}')"
                   data-on-enter="$('#modal-form [name=password]').focus()">
            @if (config('auth.registration.email_confirmation'))
                <p class="help-block">{{ trans('registration.email_notice') }}</p>
            @endif
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password1">
            {{ trans('registration.password1_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('auth/validate-registration-form') }}');"
                   data-on-enter="$('#modal-form [name=password_confirmation]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_confirmation">
            {{ trans('registration.password2_label') }}:
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

    @if (ReCaptcha::isEnabled())
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                {!! ReCaptcha::getFormElement() !!}
                <div class="help-block"></div>
            </div>
        </div>
    @endif
</form>

<script>
    function validatePasswords() {
        validateFormField($('#modal-form [name=password]'), '{{ url('auth/validate-registration-form') }}');
        validateFormField($('#modal-form [name=password_confirmation]'), '{{ url('auth/validate-registration-form') }}');
    }

    var modal = $('#modal-form');

    modal.find('.modal-title').text("{{ trans('registration.form_title') }}");
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('registration.form_submit') }}");

    runModalForm(modal);
</script>
