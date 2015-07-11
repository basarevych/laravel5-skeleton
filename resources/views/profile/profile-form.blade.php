<form role="form" class="form-horizontal" method="POST" action="{{ url('profile-form') }}">
    {!! csrf_field() !!}

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">
            {{ trans('profile.name_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}"
                   data-on-blur="validateFormField($('#modal-form [name=name]'), '{{ url('validate-profile-form') }}')"
                   data-on-enter="$('#modal-form [name=email]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">
            {{ trans('profile.email_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" value="{{ old('email', $user->email) }}"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('validate-profile-form') }}')"
                   data-on-enter="$('#modal-form [name=password]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password1">
            {{ trans('profile.password1_label') }}:
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('validate-profile-form') }}')"
                   data-on-enter="$('#modal-form [name=password_confirmation]').focus()">
            <p class="help-block">{{ trans('profile.password_notice') }}</p>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_confirmation">
            {{ trans('profile.password2_label') }}:
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
        validateFormField($('#modal-form [name=password]'), '{{ url('validate-profile-form') }}');
        validateFormField($('#modal-form [name=password_confirmation]'), '{{ url('validate-profile-form') }}');
    }

    var modal = $('#modal-form');

    modal.find('.modal-title').text("{{ trans('profile.form_title') }}");
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('profile.form_submit') }}");
    modal.find('.modal-footer .footer-text').hide();

    runModalForm(modal);
</script>
