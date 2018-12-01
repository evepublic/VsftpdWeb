<h1>General Settings</h1>

<?php if ($this->session->flashdata('general_settings_updated')) { ?>
	<div class="success">
		General Settings updated
	</div>
	<br>
<?php } ?>

<?= form_open('settings/change'); ?>
<input type=hidden name="general_settings" value="1">
<table align=center>

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">General Settings</th>
	</tr>

	<tr>
		<td>Site Name:</td>
		<td><input type='text' name="site_name" size=30 value="<?= $site_name ?>"></td>
	</tr>
	<tr>
		<td>Path of log file:</td>
		<td><input type='text' name="log_path" size=30 value="<?= $log_path ?>"></td>
	</tr>
	<tr>
		<td>Default user dir(only in web):</td>
		<td><input type='text' name="user_path" size=30 value="<?= $user_path ?>"></td>
	</tr>
	<tr>
		<td>Disk path:</td>
		<td><input type='text' name="disk1" size=30 value="<?= $getdisk1 ?>"></td>
		<td width=50><input type='text' name="def_disk1" size=30 value="<?= $getdisk1_def ?>"></td>
	</tr>
	<tr>
		<td>Disk2:</td>
		<td><input type='text' name="disk2" size=30 value="<?= $getdisk2 ?>"></td>
		<td width=50><input type='text' name="def_disk2" size=30 value="<?= $getdisk2_def ?>"></td>
	</tr>
	<tr>
		<td>Disk3:</td>
		<td><input type='text' name="disk3" size=30 value="<?= $getdisk3 ?>"></td>
		<td width=50><input type='text' name="def_disk3" size=30 value="<?= $getdisk3_def ?>"></td>
	</tr>

	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Save Settings"></td>
	</tr>

</table>
<?= form_close(); ?>

<?php if ($this->session->flashdata('mail_settings_updated')) { ?>
	<div class="success">
		Mail Settings updated
	</div>
	<br>
<?php } ?>

<?= form_open('settings/change'); ?>
<input type=hidden name="mail_settings" value="1">
<table align=center>

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">Mail Settings</th>
	</tr>

	<tr>
		<td>Mail Server:</td>
		<td><input type='text' name="mail_server" size=30 value="<?= $mail_server ?>"></td>
	</tr>
	<tr>
		<td>Mail Server port:</td>
		<td><input type='text' name="mail_port" size=30 value="<?= $mail_port ?>"></td>
	</tr>
	<tr>
		<td>Mail User:</td>
		<td><input type='text' name="mail_user" size=30 value="<?= $mail_user ?>"></td>
	</tr>
	<tr>
		<td>Mail User password:</td>
		<td><input type="password" name="mail_password" size=30 value="<?= $mail_password ?>"></td>
	</tr>
	<tr>
		<td>Mail From:</td>
		<td><input type='text' name="mail_from" size=30 value="<?= $mail_from ?>"></td>
	</tr>

	<tr>
		<td colspan="3" align=center><input type="submit" name="submit" value="Save Settings"></td>
	</tr>

</table>
<?= form_close(); ?>

<?php if ($this->session->flashdata('password_changed')) { ?>
	<div class="success">
		Password changed successfully
	</div>
	<br>
<?php } ?>

<?= form_open('settings/changepassword'); ?>
<input type=hidden name=newpassword value=0>
<table align="center">

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

