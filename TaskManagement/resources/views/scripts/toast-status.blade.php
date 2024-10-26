<?php
/**
 * Created by PhpStorm.
 * User: AD
 * Date: 30/07/24
 * Time: 2:01 pm
 */
?>

<script>
    @if (session('toast_success'))
        toastr["success"]("{{session('toast_success')}}")
    @endif
        @if (session('toast_error'))
        toastr["error"]("{{session('toast_success')}}")
    @endif
</script>

