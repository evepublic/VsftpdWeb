<h1 id="login"><?= $title ?></h1>

<?= form_submit_flash_message('login'); ?>

<?= form_open('login/process', ['class' => 'form-horizontal']); ?>

<div class="form-group">
	<label class="control-label col-sm-1" for="username">Username:</label>
	<div class="col-sm-3">
		<input type='text' class="form-control" id="username" name="username" placeholder="Enter username" required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-1" for="password">Password:</label>
	<div class="col-sm-3">
		<input type='password' class="form-control" id="password" name="password" placeholder="Enter password" required>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-1 col-sm-3">
		<input class="btn btn-default" type="submit" value="Log In">
	</div>
</div>

<?= form_close(); ?>
