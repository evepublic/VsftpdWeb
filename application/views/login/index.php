<div class="grid_16">
	<h1>Login</h1>
</div>

<?= form_open('login/process'); ?>
<table>

	<colgroup>
		<col width="10%">
	</colgroup>

	<tr>
		<td>Username:</td>
		<td><input type="text" name="username" size=30></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="password" size=30></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Log In"></td>
	</tr>

</table>
<?= form_close(); ?>

