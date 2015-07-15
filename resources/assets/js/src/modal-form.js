/*
    Show Bootstrap alert

    cbDismiss (optional) is the callback called when the form is dismissed
*/
function bsAlert(msg, title, cbDismiss) {
    var modal = $('#modal-form');
    modal.find('.loading').hide();
    modal.find('.loaded').show();
    modal.find('.modal-title').text(title);
    modal.find('.modal-body').html(msg);
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').hide();
    modal.find('button.form-close').show();
    modal.find('button.form-submit').hide();

    if (typeof cbDismiss != 'undefined')
        modal.one('hidden.bs.modal', function() { cbDismiss(); });

    modal.modal('show');
}

/*
    Show Bootstrap confirmation dialog

    cbDismiss is optional
*/
function bsConfirm(msg, title, button, cbSubmit, cbDismiss) {
    var modal = $('#modal-form');
    modal.find('.loading').hide();
    modal.find('.loaded').show();
    modal.find('.modal-title').text(title);
    modal.find('.modal-body').html(msg);
    modal.find('.modal-footer .footer-text').hide();
    modal.find('.modal-footer .spinner').hide();
    modal.find('.modal-footer .buttons').show();
    modal.find('button.form-cancel').show();
    modal.find('button.form-close').hide();

    modal.find('button.form-submit')
        .text(button)
        .off('click')
        .on('click', function () { cbSubmit(); })
        .show();

    if (typeof cbDismiss != 'undefined')
        modal.one('hidden.bs.modal', function() { cbDismiss(); });

    modal.modal('show');
}

/*
    Fetch content and display a Bootstrap modal form
*/
function openModalForm(url) {
    var modal = $('#modal-form');
    modal.find('.loading').show();
    modal.find('.loaded').hide();

    modal.modal('show');

    $.ajax({
        url: url,
        success: function (html) {
            modal.find('.modal-body').html(html);
            modal.find('.loading').hide();
            modal.find('.loaded').show();
        }
    });
}

/*
    This will initiate focus for the form
*/
function setFormFocus(form) {
    if (!form.is(':visible'))
        return;

    var parents = form.find('.has-error');
    if (parents.length == 0) 
        parents = form;

    parents.find('.form-control, input')
          .each(function (index, element) {
                var el = $(element);
                if (el.is(':visible') && !el.prop('disabled') && !el.prop('readonly')) {
                    el.focus();
                    return false;
                }
          });
}

/*
    This function will install event handlers for the modal ajax form:
    'submit' buttons will ajaxSubmit() the form
*/
function runModalForm(modal) {
    var spinner = modal.find('.modal-footer .spinner'),
        buttons = modal.find('.modal-footer .buttons');

    spinner.hide();

    modal.find('.modal-footer button.form-submit')
        .show()
        .off('click')
        .on('click', function () {
            buttons.hide();
            spinner.show();

            modal.find('form').ajaxSubmit({ // jQuery form plugin
                success: function (data) {
                    modal.find('.modal-body').html(data);
                },
                statusCode: {
                    422: function (data) {
                        spinner.hide();
                        buttons.show();

                        var form = modal.find('form');
                        form.serializeArray().forEach(function (field) {
                            var element = $('#modal-form [name=' + field['name'] + ']');
                            var errors = data['responseJSON'][field['name']];
                            showValidationErrors(element, errors);
                        });

                        setFormFocus(modal);
                    },
                },
            });
        });

    setFormFocus(modal.find('form'));
}

/*
    We expect the form to be created like this (errors will go into 'help-block' div):
    <form action="the/action/here">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="my-input">
                The label
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="my-input" id="my-input">
                <div class="help-block"></div>
            </div>
        </div>
    </form>
*/
function showValidationErrors(element, errors) {
    if (element.length == 0 || element.attr('type') == 'hidden')
        return;
    var group = element.closest('.form-group');
    if (group.length == 0)
        return;
    var helpBlock = group.find('div.help-block');
    if (helpBlock.length == 0)
        return;

    if (typeof errors == 'undefined' || errors.length == 0) {
        group.removeClass('has-error');
        helpBlock.empty();
    } else {
        group.addClass('has-error');

        var newBlock = $('<div class="help-block"></div>');
        var ul = $('<ul class="list-unstyled icon-list error-list"></ul>');
        errors.forEach(function (item) {
            $('<li></li>').text(item).appendTo(ul);
        });
        newBlock.append(ul);
        helpBlock.replaceWith(newBlock);
    }
}

/*
    The following will validate the field and display errors in the help-block div of invalid elements:
    validateFormField(element, 'url/of/validator');
        
    Validation request is sent as POST:
    {
        field: 'my-input',
        form: {
            'my-input': 'the value',
            'other-control': 'other value',
            // ... rest of the form controls
        }
    }
    'field' field is the name of the control to be validated, 'form' field is all the form controls.

    When the field is valid the server should respond with json data:
    {
        valid: true,
        errors: []
    }

    And when the field is invalid:
    {
        valid: false,
        errors: [
            'error message 1,
            'error message 2
        ]
    }
*/
function validateFormField(element, url) {
    var form = element.closest('form');
    var name = element.attr('name');
    var value = element.val();
    var timestamp = new Date().getTime();
    var serialized = form.serializeArray();

    if (!element.is(':visible') || serialized.length == 0)
        return;

    var data = {};
    $.each(serialized, function (index, item) {
        data[item['name']] = item['value'];
    });

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            field: name,
            form: data,
        },
        success: function (data) {
            var validation = form.data('validation-' + name);
            if (typeof validation == 'undefined') {
                validation = {
                    valid: true,
                    timestamp: 0,
                    errors: []
                };
            }

            // Handle out-of-order replies
            if (timestamp < validation['timestamp'])
                return;

            validation = {
                valid: data.valid,
                timestamp: timestamp,
                errors: data.errors,
            };
            form.data('validation-' + name, validation);

            showValidationErrors(element, validation['errors']);
        },
    });
}
