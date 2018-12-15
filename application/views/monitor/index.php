<h1><?= $title ?></h1>

<h2>Service</h2>

<?php if (isset($mon1['name']) and $mon1['name'] === 'listener') { ?>
	<div class="panel panel-success">
		<div class="panel-heading">VSFTPD server is online</div>
	</div>
<?php } else { ?>
	<div class="panel panel-danger">
		<div class="panel-heading">VSFTPD server is offline</div>
	</div>
<?php } ?>

<h2>Active users</h2>

<table class="table">
	<tr>
		<th>Username</th>
		<th>Action</th>
		<th>Start Time</th>
		<th>IP Address</th>
		<th>pid</th>
		<th>FTP Command</th>
	</tr>

	<?php
	if (isset($mon1['children'])) foreach ($mon1['children'] as $child) {
		if (isset($child['children'])) foreach ($child['children'] as $child2) {
			?>
			<tr>

				<td>
					<?= htmlentities($child2['user']) ?>
				</td>

				<td>
					<?php switch ($child2['command']) {
						case 'IDLE':
							echo 'Idle';
							break;
						case 'STOR':
							echo 'Uploading file: ' . htmlentities($child2['parameter']);
							break;
						case 'RETR':
							echo 'Downloading file: ' . htmlentities($child2['parameter']);
							break;
					} ?>
				</td>

				<td>
					<?= $child2['starttime'] ?>
				</td>

				<td>
					<?= $child2['ip'] ?>
				</td>

				<td>
					<?= $child2['ppid'] ?>
				</td>

				<td>
					<?= $child2['command'] . ' ' . htmlentities($child2['parameter']); ?>
				</td>

			</tr>

			<?php
		}
	}
	?>

</table>
