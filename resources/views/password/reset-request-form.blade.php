<form role="form" class="form-horizontal" method="POST" action="{{ url('auth/reset-request-form') }}">
    {!! csrf_field() !!}

    @if (session('message'))
        <div class="alert alert-danger">
            <center>{{ session('message') }}</center>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">
            {{ trans('password.email_label') }}:
            <span class="required-marker text-danger">
                {{ trans('messages.required_field') }}
            </span>
        </label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" value="{{ old('email') }}"
                   data-on-blur="validateFormField($('#modal-form [name=email]'), '{{ url('auth/validate-request-form') }}')"
                   data-on-enter="$('#modal-form [type=submit]').focus().click()">
            <div class="help-block"></div>
        </div>
    </div>
</form>

<script>
    var modal = $('#modal-form');

    modal.find('.modal-title').text('{{ trans('password.request_title') }}');
    modal.find('button[type=submit]').show().text('{{ trans('password.request_submit') }}');
    modal.find('.modal-footer .footer-text').hide();

    runModalForm(modal);
</script>
