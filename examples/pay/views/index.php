<?php include "header.php"; ?>

<h1>Basket:</h1>

<form method="POST" action="?action=checkout">
	<table>
		<tbody>
			<tr><td>NIP:</td><td><input type="number" name="buyer_gov_id" value="8429067910"></td></tr>
			<tr><td>Total:</td><td><input type="number" name="price_gross" value="250.00"></td></tr>
		</tbody>
	</table>
	<button type="submit">Checkout &gt;&gt;</button>
</form>

<?php include "footer.php"; ?>