<h1>Error in password validation, try again</h1>

<div class="error">
	<?= validation_errors(); ?>
	<?php if (isset($password_change_error)) { ?>
		<p><?= $password_change_error ?></p>
	<?php } ?>
</div>
<br>

<?= form_open('settings/changepassword'); ?>
<table>

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">Change Password</th>
	</tr>

	<tr>
		<td>Current password:</td>
		<td><input type="password" name="currentpassword" size=30></td>
	</tr>
	<tr>
		<td>New password:</td>
		<td><input type="password" name="newpassword" size=30></td>
	</tr>
	<tr>
		<td>Confirm new password:</td>
		<td><input type="password" name="newpasswordconfirm" size=30></td>
	</tr>

	<tr>
		<td colspan=3 align=center><input type="submit" value="Save Password"></td>
	</tr>

</table>
<?= form_close(); ?>

