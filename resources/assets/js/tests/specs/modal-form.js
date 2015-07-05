'use strict'

describe("Modal form", function() {
    beforeEach(function (done) {
        $.ajax({
            url: '../../../resources/assets/js/tests/fixtures/modal-form.html',
            dataType: 'html',
            success: function (html) {
                var fixture = $('<div id="fixture"></div>');
                $('body').append(fixture.append(html));
                done();
            }
        });
    });

    afterEach(function () {
        $('#fixture').remove();
    });

    it("initializes ", function (done) {
        var modal = $('#modal-form'),
            button = modal.find('button[type=submit]');

        spyOn($, 'ajax').and.callFake(function (params) {
            params.success('FOOBAR');
            expect(modal.find('.modal-body')).toHaveText('FOOBAR');

            done();
        });

        openModalForm('http://example.com');
        button.trigger('click');
    });

    it("sets focus ", function () {
        var modal = $('#modal-form');
        modal.modal('show');
        setFormFocus(modal.find('form'));

        expect($('#field')).toBeFocused();
    });

    it("validates correct form field ", function (done) {
        spyOn($, 'ajax').and.callFake(function (params) {
            expect(params['url']).toBe('/example/validate-form');
            expect(params['data']).toEqual({
                field: 'field',
                form: {
                    security: 'hash',
                    field: 'foobar',
                },
            });

            params.success({
                valid: true,
                errors: [],
            });

            expect($('#field').closest('.form-group')).not.toHaveClass('has-error');
            done();
        });

        validateFormField($('#field'), '/example/validate-form');
    });

    it("validates invalid form field ", function (done) {
        spyOn($, 'ajax').and.callFake(function (params) {
            expect(params['url']).toBe('/example/validate-form');
            expect(params['data']).toEqual({
                field: 'field',
                form: {
                    security: 'hash',
                    field: 'foobar',
                },
            });

            params.success({
                valid: false,
                errors: [ 'MESSAGE' ],
            });

            var group = $('#field').closest('.form-group');
            expect(group).toHaveClass('has-error');
            expect(group.find('.help-block li')).toHaveText('MESSAGE');
            done();
        });

        validateFormField($('#field'), '/example/validate-form');
    });

    it("runs ", function (done) {
        var modal = $('#modal-form');

        spyOn($.fn, 'ajaxSubmit').and.callFake(function (params) {
            var spinner = modal.find('.modal-footer .spinner'),
                buttons = modal.find('.modal-footer .buttons');

            expect(spinner).not.toHaveCss({ display: 'none' });
            expect(buttons).toHaveCss({ display: 'none' });

            var func = params.statusCode['422'];
            func({ responseJSON: { field: [ 'MESSAGE' ] }});

            expect(spinner).toHaveCss({ display: 'none' });
            expect(buttons).not.toHaveCss({ display: 'none' });

            var group = $('#field').closest('.form-group');
            expect(group).toHaveClass('has-error');
            expect(group.find('.help-block li')).toHaveText('MESSAGE');

            params.success('FOOBAR');
            expect(modal.find('.modal-body')).toHaveText('FOOBAR');

            done();
        });

        runModalForm(modal);
        modal.find('.modal-footer button.form-submit').click();
    });
});
