<nav class="navbar navbar-inverse">

	<div class="container-fluid" id="navbartop">

		<div class="navbar-header">
			<span class="navbar-brand">VsftpdWeb FTP Administration: <?= $site_name_display ?></span>
		</div>

		<ul class="nav navbar-nav navbar-right diskspace">
			<li><?= $disk['path'] ?></li>
			<li><?= $disk['space'] ?> free</li>
		</ul>

	</div>

	<div class="container-fluid" id="navbarbottom">

		<ul class="nav navbar-nav">
			<li><a href="<?= site_url('monitor'); ?>">Service Monitor</a></li>
			<li><a href="<?= site_url('log'); ?>">FTP Log</a></li>
			<li><a href="<?= site_url('users'); ?>">FTP User Management</a></li>
			<li><a href="<?= site_url('settings'); ?>">Settings</a></li>
		</ul>

		<ul class="nav navbar-nav navbar-right">
			<li><a href="<?= site_url('logout'); ?>">Log Out</a></li>
		</ul>

	</div>

</nav>
