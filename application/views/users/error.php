<h1>Error in password validation, try again</h1>

<div class="error">
	<?php echo validation_errors(); ?>
</div>
<br>

<?= form_open('users/changepassword'); ?>
<input type="hidden" name="id" value="<?= $this->input->post('id'); ?>">

<table>

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">New Password</th>
	</tr>
	<tr>
		<td>New password:</td>
		<td><input type="password" name="upass" size=30></td>
	</tr>
	<tr>
		<td>Confirm new password:</td>
		<td><input type="password" name="repass" size=30></td>
	</tr>
	<tr>
		<td><input type="submit" value="Save password"></td>
	</tr>

</table>
<?= form_close(); ?>

