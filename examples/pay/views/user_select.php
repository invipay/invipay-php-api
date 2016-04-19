<?php include "header.php"; ?>

<h1>Please select an employee:</h1>

<form method="POST" action="?action=confirm_sms_input">
	<ul class="users_list">

	</ul>

	<button type="submit">Confirm your order</button>
</form>

<script type="text/javascript">
	var paymentId = '<?= $_SESSION[SESSION_KEY]; ?>';

	$(document).ready(function()
	{
		waitForNewAsyncData(paymentId, 0, '.users_list');
	});
</script>

<?php include "footer.php"; ?>