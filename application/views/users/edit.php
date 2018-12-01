<h1>Change FTP User settings : <?= $user_item['username'] ?></h1>
<?= form_open('users/change'); ?>
<input type="hidden" name="id" value="<?= $user_item['id'] ?>">
<input type="hidden" name="username" value="<?= $user_item['username'] ?>">
<input type="hidden" name="disk1" value="<?= $getdisk1 ?>">
<input type="hidden" name="disk2" value="<?= $getdisk2 ?>">

<table align="center">

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<td>Select path:</td>
		<td>
			<input type="radio" name="dir" value="def" <?php if ($checked == 1) echo 'checked'; ?> /> Default user path
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="radio" name="dir" value="disk1" <?php if ($checked == 2) echo 'checked'; ?> /> <?= $getdisk1 ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="radio" name="dir" value="disk2" <?php if ($checked == 3) echo 'checked'; ?> /> <?= $getdisk2 ?>
		</td>
	</tr>
	<tr>
		<td>Home dir:</td>
		<td>
			<input type="text" name="path" size=30 value="<?php if ($user_item['path'] != 'none') echo $checkpath; ?>">
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="checkbox" name="write" value="yes" <?php if ($user_item['perm'] == 'w' | $user_item['perm'] == 'wd') echo 'checked'; ?> />Write / Upload access
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="checkbox" name="delete" value="yes" <?php if ($user_item['perm'] == 'wd') echo 'checked'; ?> />Delete / Rename restriction
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Save"></td>
	</tr>

</table>
<?= form_close(); ?>

<h1>Change FTP User Password</h1>
<?= form_open('users/changepassword'); ?>
<input type="hidden" name="id" value="<?= $user_item['id'] ?>">

<table align="center">

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">New Password</th>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="upass" size=30></td>
	</tr>
	<tr>
		<td>Confirm Password:</td>
		<td><input type="password" name="repass" size=30></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Save"></td>
	</tr>

</table>
<?= form_close(); ?>

