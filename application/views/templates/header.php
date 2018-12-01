<h1 id="head">
	<span class="headspan">VsftpdWeb FTP Administration : <?= $site_name ?></span>

	<span class='free'>
		<ul class="disk">
			<li>Main disk : <?= $disk1['space'] ?></li>
			<li><?= $disk2['disk'] . ' : ' . $disk2['space'] ?></li>
			<li><?= $disk3['disk'] . ' : ' . $disk3['space'] ?></li>
		</ul>
	</span>
</h1>

<ul id="navigation">
	<li><a href="<?= site_url('monitor'); ?>">FTP Monitor</a></li>
	<li><a href="<?= site_url('log'); ?>">FTP Log</a></li>
	<li><a href="<?= site_url('users'); ?>">FTP User Settings</a></li>
	<li><a href="<?= site_url('settings'); ?>">General Settings</a></li>
	<li><a href="<?= site_url('logout'); ?>">Log Out</a></li>
</ul>
