<h1><?= $title ?></h1>

<?php if (isset($log_data['error'])) { ?>
	<div class="alert alert-danger">
		<?= $log_data['error'] ?>
	</div>
<?php } ?>

<table class="table">

	<tr>
		<th>Date</th>
		<th>Time</th>
		<th>Remote host</th>
		<th>User</th>
		<th>File Name</th>
		<th>Size</th>
		<th>Transfer time</th>
		<th>Action</th>
		<th>Status</th>
	</tr>

	<?php if (!isset($log_data['error'])) foreach ($log_data as $record) { ?>
		<tr>
			<td><?= htmlentities($record['date']); ?></td>
			<td><?= htmlentities($record['time']); ?></td>
			<td><?= htmlentities($record['remotehost']); ?></td>
			<td><?= htmlentities($record['username']); ?></td>
			<td><?= htmlentities($record['filename']); ?></td>
			<td><?= htmlentities($record['filesize']); ?></td>
			<td><?= htmlentities($record['transfertime']); ?></td>
			<td><?= htmlentities($record['action']); ?></td>
			<td><?= htmlentities($record['status']); ?></td>
		</tr>
	<?php } ?>
</table>
