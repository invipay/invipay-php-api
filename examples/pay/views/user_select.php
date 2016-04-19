<?php include "header.php"; ?>

<h1>Wybierz pracownika:</h1>

<form method="POST" action="?action=confirm_sms_input">
	<ul class="users_list">

	</ul>

	<button type="submit" name="action" value="confirm_sms_input">Potwierdź płatność</button>
	<button type="submit" name="action" value="add_employee">Dodaj nowego pracownika</button>
</form>

<script type="text/javascript">
	var paymentId = '<?= $_SESSION[SESSION_KEY]; ?>';

	$(document).ready(function()
	{
		waitForNewAsyncData(paymentId, 0, '.users_list');
	});
</script>

<?php include "footer.php"; ?>