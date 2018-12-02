<h1>Users</h1>
<table class="users" align="center">

	<tr>
		<th>Username</th>
		<th></th>
		<th></th>
		<th>Path</th>
		<th>Permissions</th>
	</tr>

	<?php foreach ($users as $user_item) {
		$del = site_url('users/delete/' . $user_item['id']);
		$pw = site_url('users/edit/' . $user_item['id']);

		if ($user_item['path'] == 'none') $path = $def_path . $user_item['username'];
		else $path = $user_item['path'];

		if ($user_item['perm'] == 'r' || $user_item['perm'] == '0') $perm = 'Read';
		else if ($user_item['perm'] == 'w') $perm = 'Read / Write';
		else if ($user_item['perm'] == 'wd') $perm = 'Read / Write (Delete / Rename restriction)';
		?>

		<tr>
			<td><strong><?= $user_item['username'] ?></strong></td>
			<td><a href="<?= $del ?>" class="delete">Delete</a></td>
			<td><a href="<?= $pw ?>" class="edit">Settings</a></td>
			<td><?= $path ?></td>
			<td><?= $perm ?></td>
		</tr>

	<?php } ?>

</table>


<h1 id="newuser">New FTP User</h1>

<?php if (validation_errors()) { ?>
	<div class="error">
		<?= validation_errors(); ?>
	</div>
	<br>
<?php } ?>

<?php if ($this->session->flashdata('user_created')) { ?>
	<div class="success">
		User '<?= $this->session->flashdata('user_created') ?>' was created successfully
	</div>
	<br>
<?php } ?>

<?= form_open('users#newuser'); ?>

<table align="center">

	<colgroup>
		<col width="20%">
		<col width="20%">
		<col width="60%">
	</colgroup>

	<tr>
		<th colspan="3">New Username</th>
	</tr>

	<tr>
		<td>Username:</td>
		<td><input type="text" name="user" size="30"></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="upass" size="30"></td>
	</tr>
	<tr>
		<td>Confirm Password:</td>
		<td><input type="password" name="repass" size="30"></td>
	</tr>

	<tr>
		<td>Select path:</td>
		<td><input type="radio" name="dir" value="def" checked/> Default user path</td>
		<td><input type="radio" name="dir" value="custom"/> Custom path</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="text" size="30" value="<?= $def_path ?>" disabled="disabled"></td>
		<td><input type="text" name="path" size="30" value=""></td>
	</tr>

	<tr>
		<td colspan="3" align="center"><input type="submit" name="create_user_submit" value="Create User"/></td>
	</tr>

</table>

<?= form_close(); ?>

