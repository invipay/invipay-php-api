<?php include "header.php"; ?>

<h1>Potwierdź dodanie nowych pracowników</h1>

<p>Wysłaliśmy SMS z kodem autoryzacyjnym do <strong><?= $smsData->getRecipient(); ?></strong>, na numer telefonu <strong><?= $smsData->getPhoneHint(); ?></strong>.</p>

<form method="POST" action="?action=confirm_add_employees">
	<table>
		<tr><td>Wpisz otrzymany kod:</td><td><input type="number" name="sms_code" value="" placeholder="Demo: 1111"></td></tr>
	</table>

	<button type="submit">Zakończ dodawanie pracowników</button>
</form>

<?php include "footer.php"; ?>