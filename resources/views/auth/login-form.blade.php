<form role="form" class="form-horizontal" method="POST" action="{{ url('auth/login-form') }}">
    {!! csrf_field() !!}

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">
            {{ trans('auth.email_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" value="{{ old('email') }}"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('auth/validate-login-form') }}')"
                   data-on-enter="$('#modal-form [name=password]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="password">
            {{ trans('auth.password_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password"
                   data-on-blur="validateFormField($('#modal-form [name=password]'), '{{ url('auth/validate-login-form') }}')"
                   data-on-enter="$('#modal-form [name=remember_me]').focus()">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember_me" value="1"
                           {!! (session('message') ? old('remember_me') : true) ? 'checked="checked"' : '' !!}
                           data-on-enter="$('#modal-form [type=submit]').focus().click()">
                    {{ trans('auth.remember_me_label') }}
                </label>
            </div>
        </div>
    </div>
</form>

<script>
    var modal = $('#modal-form');

    modal.find('.modal-title').text("{{ trans('auth.form_title') }}");
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();
    modal.find('button.form-submit').show().text("{{ trans('auth.form_submit') }}");
    modal.find('.modal-footer .footer-text').show().html(
        "<a href=\"javascript:$('#modal-form').modal('hide');openModalForm('{{ url('/auth/reset-request-form') }}')\">{{ trans('auth.reset_link') }}</a>"
    );

    runModalForm(modal);
</script>
