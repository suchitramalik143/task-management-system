(function (define) {
    define(['jquery'], function ($) {
        return (function () {
            var appLoader = {
                show: show,
                hide: hide,
                options: {
                    container: 'body',
                    zIndex: "auto",
                    css: "",
                    text: "Processing..",
                }
            };

            return appLoader;

            function show(icon=true,options) {
                const $template = $("#app-loader");
                this._settings = _prepear_settings(options);
                if (!$template.length) {
                    const $container = $(this._settings.container);
                    $container.addClass('fixed-body');
                    if ($container.length) {
                        let html ='<div id="app-loader" class="app-loader" style="' + this._settings.css + '"><div class="loading d-flex align-items-center flex-column '+(icon?'has-img':'')+'">';
                        if (icon){
                            html = html+'<img src="/images/loading_animation.gif"/>';
                        }else{
                            html = html+'<span class="loader"></span>';
                        }
                        html = html+ this._settings.text + '</div></div>';
                        $container.append(html);
                    } else {
                        console.log("appLoader: container must be an html selector!");
                    }

                }
            }

            function hide() {
                $(this._settings.container).removeClass('fixed-body');
                let $template = $("#app-loader");
                if ($template.length) {
                    $template.remove();
                }
            }

            function _prepear_settings(options) {
                if (!options)
                    var options = {};
                return this._settings = $.extend({}, appLoader.options, options);
            }
        })();
    });
}(function (d, f) {
    window['appLoader'] = f(window['jQuery']);
}));


$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });


    /**
     * Submit form by external button click
     */
    $('.form-submit-btn').on('click', function () {
        const target = $(this).attr('data-target');
        $('#' + target).find('button[type="submit"]').click();
    })

    //Bulk selection
    $('body').on('click', '[data-act=select-input]', function (e) {
        let $selector = $(this),
            name = $selector.attr('data-select-name'),
            type = 'checkbox';

        if ($selector.attr('data-select-type')) {
            type = $selector.attr('data-select-type');
        }
        if ($selector.is(":checked")) {
            $('input:' + type + '[name="' + name + '"]').prop('checked', true);
        } else {
            $('input:' + type + '[name="' + name + '"]').prop('checked', false);
        }
    });


    // Bulk selection counter
    $('.input-checkbox-cell').on('change', function () {
        const parentTable = $(this).closest('table');
        const selectingInput = parentTable.find("input:checkbox[data-act='select-input']");
        const name = selectingInput.attr('data-select-name');
        const selectedField = $('input:checkbox[name="' + name + '"]:checked').length;
        if (selectedField === 0) {
            selectingInput.prop("checked", false)
            $('.selected-input-count').html("");
        } else {
            $('.selected-input-count').html("(" + selectedField + " selected)");
        }
    })

    // Collapsable row
    $('.clickable-row').on('click', function (e) {
        let hasClass = $(this).hasClass('show-detail');
        $('.clickable-row').removeClass('show-detail');
        if (!hasClass) {
            $(this).addClass('show-detail');
        }
    });
    $('.clickable-row').find('button').on('click', function (e) {
        e.stopPropagation();
    })
    $('.clickable-row').find('input').on('click', function (e) {
        e.stopPropagation();
    })

    // Disable submit button on click
    $('form').on('submit', function (e) {
        const btn = $(this).find('button:not([type="button"])');
        const submitBtn = $(this).find('button[type="submit"]');
        if (!submitBtn.attr('data-ignore-disable')) {
            submitBtn.prop('disabled', true);
        }
        if (!btn.attr('data-ignore-disable')) {
            btn.prop('disabled', true);
        }
    })

    // Remove any error class if input is changed
    $('body').on('keyup', 'input, select, textarea', function () {
        $(this).removeClass('has-error');
    });

    $('body').on('click', '[data-loader]', function () {
        const text = $(this).attr('data-loader');
        let payload = {};
        if (text.length) {
            payload.text = text;
        }
        appLoader.show(payload);
    })

    $('body').on('click', '[data-action=validate-form-submit]', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        let hasError = false;

        // Get all input, select, or textarea fields with required properties
        form.find('input[required], select[required], textarea[required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('has-error');
                hasError = true;
            } else {
                $(this).removeClass('has-error');
            }
        });

        // If there are no errors, submit the form
        if (!hasError) {
            form.submit();
        }
    });




    // Download selected and all
    $('.download-selected').on('click', function (e) {
        e.preventDefault();
        const form = $($(this).attr('data-bs-target'));
        const label = $(this).attr('data-label');
        let ids = $("input:checkbox[name='id[]']:checked").map(function () {
            return $(this).val()
        }).get();
        form.find('input[name="selected_ids"]').remove();
        if (ids.length) {
            form.append('<input class="d-none" type="hidden" name="selected_ids" value=' + ids + '>');
            form.submit();
        } else {
            toastr["error"](`Please select at-least one ${label}.`)
        }
    });

    $('.download-all').on('click', function (e) {
        e.preventDefault();
        const form = $($(this).attr('data-bs-target'));
        form.find('input[name="selected_ids"]').remove();
        form.submit();
    });

})

