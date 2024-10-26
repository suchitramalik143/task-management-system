<script type="text/javascript">

    // CONFIRMATION SAVE MODEL
    $('#ajaxModal').on('show.bs.modal', function (e) {
        let title = $(e.relatedTarget).attr('data-title');
        const url = $(e.relatedTarget).attr('data-url');
        const method = $(e.relatedTarget).attr('data-method') ? $(e.relatedTarget).attr('data-method') : 'post';
        const additionalClass = $(e.relatedTarget).attr('data-class') ? $(e.relatedTarget).attr('data-class') : null;
        $(this).find('.modal-title').text(title);
        $(this).find('#ajaxModalContent').html($('#ajaxModalOriginalContent').html());
        let data = {};

        if (additionalClass) {
            $('#ajaxModal').find('.modal-dialog').addClass(additionalClass);
        }

        //Fire Ajax
        if (url !== undefined) {
            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                },
                success: function (response) {
                    if (response.code == 200 || response.success) {
                        $('#ajaxModal').find('.modal-dialog').removeClass('mini-modal');
                        $('#ajaxModalContent').html(response.value);
                    } else {
                        closeAjaxModel(additionalClass);
                        toastr["warning"](response.msg ? response.msg : "Failed to get requested data.");

                    }
                },
                error: function (res) {
                    //Close ajax model
                    console.log('errir', res)
                    closeAjaxModel(additionalClass);
                    toastr["error"]("Exception: Failed to get requested data.");
                },
                statusCode: {
                    500: function () {
                        alert("Script exhausted");
                    }
                }
            });
        }
    });

    function closeAjaxModel(additionalClass = null) {
        if (additionalClass) {
            $('#ajaxModal').find('.modal-dialog').removeClass('additionalClass');
        }
        $('#ajaxModal').find('.modal-dialog').addClass('mini-modal');
        $('#ajaxModal').modal('hide');

    }
</script>
