<h1><?= $title ?></h1>


<h2 id="settings_update">VsftpdWeb settings</h2>

<?= form_submit_flash_message('settings_update'); ?>

<?= form_open('settings/update', ['class' => 'form-horizontal']); ?>

<div class="form-group">
	<label class="control-label col-sm-3" for="site_name">Site name:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="site_name" name="site_name" value="<?= htmlentities($site_name); ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="permissions_r">Default new user permissions:</label>
	<div class="col-sm-4">
		<div>
			<input type="radio" id="permissions_r" name="default_permissions" <?= ($default_permissions === 'r') ? 'checked' : ''; ?> value="r"> Read only
		</div>
		<div>
			<input type="radio" name="default_permissions" <?= ($default_permissions === 'wd') ? 'checked' : ''; ?> value="wd"> Read / Write (Delete / Rename restriction)
		</div>
		<div>
			<input type="radio" name="default_permissions" <?= ($default_permissions === 'w') ? 'checked' : ''; ?> value="w"> Read / Write
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="vsftpd_config_path">Vsftpd configuration file:</label>
	<div class="col-sm-3">
		<select class="form-control" id="vsftpd_config_path" name="vsftpd_config_path">
			<option <?= ($vsftpd_config_path === '/etc/vsftpd.conf') ? 'selected' : ''; ?>>/etc/vsftpd.conf</option>
			<option <?= ($vsftpd_config_path === '/etc/vsftpd/vsftpd.conf') ? 'selected' : ''; ?>>/etc/vsftpd/vsftpd.conf</option>
		</select>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-3">
		<input class="btn btn-default" type="submit" value="Save">
	</div>
</div>

<div class="form-group">
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="ftp_users_store_dir">Ftp users storage directory:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="ftp_users_store_dir" disabled value="<?= $user_base_path ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="ftp_users_config_dir">Ftp users config directory:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="ftp_users_config_dir" disabled value="<?= $user_config_dir ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="xferlog_file">Xferlog file path:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="xferlog_file" disabled value="<?= $xferlog_file ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="password_encryption_algorithm">Password encryption algorithm:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="password_encryption_algorithm" disabled value="<?= $password_encryption_algorithm ?>">
	</div>
</div>

<?= form_close(); ?>


<h2 id="settings_change_password">Change password</h2>

<?= form_submit_flash_message('settings_change_password'); ?>

<?= form_open('settings/changepassword', ['class' => 'form-horizontal']); ?>

<div class="form-group">
	<label class="control-label col-sm-3" for="currentpassword">Current password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="currentpassword" name="currentpassword" placeholder="Current password" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="newpassword">New password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New password" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3" for="confirmnewpassword">Confirm new password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" id="confirmnewpassword" name="confirmnewpassword" placeholder="Confirm new password" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-3">
		<input class="btn btn-default" type="submit" value="Change password">
	</div>
</div>

<?= form_close(); ?>
