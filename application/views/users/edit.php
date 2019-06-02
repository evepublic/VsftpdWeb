<h1><?= $title ?></h1>


<h2 id="users_update_password">Update password</h2>

<?= form_submit_flash_message('users_update_password'); ?>

<?= form_open('users/updatepassword', ['class' => 'form-horizontal']); ?>

<input type="hidden" name="user_id" value="<?= $user->id ?>">

<div class="form-group">
	<label class="control-label col-sm-2" for="password">Password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="confirmpassword">Confirm password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm password" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-3">
		<input class="btn btn-default" type="submit" value="Save password">
	</div>
</div>

<?= form_close(); ?>


<h2 id="users_update_permissions">Update permissions</h2>

<?= form_submit_flash_message('users_update_permissions'); ?>

<?= form_open('users/updatepermissions', ['class' => 'form-horizontal']); ?>

<input type="hidden" name="user_id" value="<?= $user->id ?>">

<div class="form-group">
	<label class="control-label col-sm-2" for="permissions_r">Permissions:</label>
	<div class="col-sm-4">
		<div>
			<input type="radio" id="permissions_r" name="permissions" <?= ($user->permissions === 'r') ? 'checked' : ''; ?> value="r"> Read only
		</div>
		<div>
			<input type="radio" name="permissions" <?= ($user->permissions === 'wd') ? 'checked' : ''; ?> value="wd"> Read / Write (Delete / Rename restriction)
		</div>
		<div>
			<input type="radio" name="permissions" <?= ($user->permissions === 'w') ? 'checked' : ''; ?> value="w"> Read / Write
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-3">
		<input class="btn btn-default" type="submit" value="Save">
	</div>
</div>

<?= form_close(); ?>


<h2 id="users_update_directory">Update storage directory</h2>

<?= form_submit_flash_message('users_update_directory'); ?>

<?= form_open('users/updatestoragedirectory', ['class' => 'form-horizontal']); ?>

<input type="hidden" name="user_id" value="<?= $user->id ?>">

<div class="form-group">
	<label class="control-label col-sm-2" for="storage_directory">Directory:</label>
	<div class="col-sm-2">
		<input type="text" class="form-control" disabled value="<?= $user_base_path ?>">
	</div>
	<div class="col-sm-2">
		<input type="text" class="form-control" id="storage_directory" name="storage_directory" placeholder="Enter directory" value="<?= $user->storage_directory ?>" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-3">
		<input class="btn btn-default" type="submit" value="Save">
	</div>
</div>

<?= form_close(); ?>


<h2 id="users_delete_user">Delete user</h2>

<?= form_submit_flash_message('users_delete_user'); ?>

<p>Deleting the user will remove its credentials.</p>
<p class="text-danger">THIS CANNOT BE UNDONE!</p>
<p>To confirm deletion of this user, type its username.</p>

<?= form_open('users/delete', ['class' => 'form-horizontal']); ?>

<input type="hidden" name="user_id" value="<?= $user->id ?>">
<input type="hidden" name="username" value="<?= $user->username ?>">

<div class="form-group">
	<label class="control-label col-sm-2" for="inputusername">Username:</label>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="inputusername" name="inputusername" placeholder="Enter username" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-3">
		<input class="btn btn-danger" type="submit" value="Delete user">
	</div>
</div>

<?= form_close(); ?>
