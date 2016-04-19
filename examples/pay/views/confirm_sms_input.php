<?php include "header.php"; ?>

<h1>Please confirm your payment:</h1>

<p>We've sent authorization SMS to <strong><?= $smsData->getRecipient(); ?></strong> (phone number: <?= $smsData->getPhoneHint(); ?>).</p>

<form method="POST" action="?action=confirm_final">
	<table>
		<tr><td>Received code:</td><td><input type="number" name="sms_code" value="" placeholder="In demo mode: 1111"></td></tr>
	</table>

	<button type="submit">Confirm payment</button>
</form>

<?php include "footer.php"; ?>