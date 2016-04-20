<?php include "header.php"; ?>

<h1>Twój koszyk:</h1>

<form method="POST" action="?action=checkout">
	<table>
		<tbody>
			<tr><td>NIP:</td><td><input type="number" name="buyer_gov_id" value="5270103391"></td></tr>
			<tr><td>Kwota całkowita:</td><td><input type="number" name="price_gross" value="250.00"></td></tr>
		</tbody>
	</table>
	<button type="submit">Dalej</button>
</form>

<?php include "footer.php"; ?>