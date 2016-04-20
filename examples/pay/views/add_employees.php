<?php include "header.php"; ?>

<h1>Dodaj nowych pracowników</h1>

<form method="POST" action="?action=do_add_employees">
	<table>
		<?php if (!empty($availableEmployees)): ?>
			<tr>
				<td>Pracownik autoryzujący:</td>
				<td>
					<select name="employee_id">
						<?php foreach ($availableEmployees as $employee): ?>
							<option value="<?= $employee['employeeId']; ?>"><?= $employee['firstName'].' '.$employee['lastName']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		<?php endif; ?>
		<tr><td>Imię:</td><td><input type="text" name="first_name" value="Jan"></td></tr>
		<tr><td>Nazwisko:</td><td><input type="text" name="last_name" value="Kowalski"></td></tr>
		<tr><td>e-Mail:</td><td><input type="text" name="email" value="<?= uniqid(); ?>@invipay.com"></td></tr>
		<tr><td>Telefon:</td><td><input type="number" name="phone" value="123123123"></td></tr>
	</table>

	<button type="submit">Dalej</button>
</form>

<?php include "footer.php"; ?>