

<h1>ERROR in password validation, try again.</h1>
	<?=form_open('users/changepass');?>
	<?php echo validation_errors(); ?>
	<input type="hidden" name="id" value="<?= $this->input->post('id') ?>">
	<input type=hidden name=pass value=0> 
		<table align="center">
			<tr>
				<th colspan=2>New Password</th>
			</tr>
			<tr>
				<td width=150>Change Password:</td>
				<td width=300><input type="password" name="upass" size=30></td>
			</tr>
			<tr>
				<td width=150>Confirm Password:</td>
				<td width=300><input type="password" name="repass" size=30></td>
			</tr>
			<tr>
				<td colspan=2 align=center><input type="submit" name="submit" value="Save Password"></td>
			</tr>
		</table>
	</form>

