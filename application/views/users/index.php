<h1><?= $title ?></h1>

<h2 id="users_create_user">Create a new FTP user</h2>

<?= form_submit_flash_message('users_create_user'); ?>

<?= form_open('users/create', ['class' => 'form-horizontal']); ?>

<div class="form-group">
	<label class="control-label col-sm-3" for="username">Username:</label>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="password">Password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="confirmpassword">Confirm password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="confirmpassword" placeholder="Confirm password" name="confirmpassword" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="permissions_r">Permissions:</label>
	<div class="col-sm-4">
		<div>
			<input type="radio" id="permissions_r" name="permissions" <?= ($default_permissions === 'r') ? 'checked' : ''; ?> value="r"> Read only
		</div>
		<div>
			<input type="radio" name="permissions" <?= ($default_permissions === 'wd') ? 'checked' : ''; ?> value="wd"> Read / Write (Delete / Rename restriction)
		</div>
		<div>
			<input type="radio" name="permissions" <?= ($default_permissions === 'w') ? 'checked' : ''; ?> value="w"> Read / Write
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-2">
		<input class="btn btn-default" type="submit" value="Create user">
	</div>
</div>

<?= form_close(); ?>


<h2 id="users_batchimport">Batch import FTP users</h2>

<?= form_submit_flash_message('users_batchimport'); ?>

<p>Create multiple users at once from a CSV file.</p>
<p>The CSV file can only contain new users and must have 2 columns.</p>
<ul>
	<li>username</li>
	<li>password</li>
</ul>

<?= form_open_multipart('users/importcsv', ['class' => 'form-horizontal']); ?>

<div class="form-group">
	<label class="control-label col-sm-3" for="username">Select an CSV file:</label>
	<div class="col-sm-3">
		<input type="file" class="form-control" name="import_file" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-2">
		<input class="btn btn-default" type="submit" value="Import CSV file">
	</div>
</div>

<?= form_close(); ?>


<span id="users_delete_user"></span>
<h2>All FTP users</h2>

<?= form_submit_flash_message('users_delete_user'); ?>

<table class="table">

	<tr>
		<th>Username</th>
		<th></th>
		<th>Path</th>
		<th>Permissions</th>
	</tr>

	<?php foreach ($users as $user) {

		if ($user['path'] == 'none') $path = $storage_dir . $user['username'];
		else $path = $user['path'];

		$perm = '';
		if ($user['perm'] === 'r') $perm = 'Read only';
		elseif ($user['perm'] === 'w') $perm = 'Read / Write';
		elseif ($user['perm'] === 'wd') $perm = 'Read / Write (Delete / Rename restriction)';
		?>

		<tr>
			<td><strong><?= $user['username'] ?></strong></td>
			<td><a href="<?= site_url('users/edit/' . (int)$user['id']); ?>" class="edit">Edit</a></td>
			<td><?= $path ?></td>
			<td><?= $perm ?></td>
		</tr>

	<?php } ?>

</table>
