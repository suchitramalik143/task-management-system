<script type="text/javascript">

	// CONFIRMATION SAVE MODEL
	$('#confirmSave').on('show.bs.modal', function (e) {
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		const confirmBtnTaxt = $(e.relatedTarget).attr('data-confirm-text')?$(e.relatedTarget).attr('data-confirm-text'):"Confirm";
		$(this).find('.modal-body p').text(message);
		$(this).find('.modal-title').text(title);
		$(this).find('.modal-footer #confirm').data('form', form);
        $('#confirmSave').find('.modal-footer #confirm').html('<i class="fa fa-fw fa-save me-1"></i>'+confirmBtnTaxt);
	});
	$('#confirmSave').find('.modal-footer #confirm').on('click', function(){
	  	$(this).data('form').submit();
	});

</script>
