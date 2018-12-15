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
		<th>Transfer time</th>
		<th>Size</th>
		<th>State</th>
		<th>User</th>
		<th>File Name</th>
	</tr>

	<?php if (!isset($log_data['error'])) foreach ($log_data as $record) { ?>
		<tr>
			<td><?= htmlentities($record['date']); ?></td>
			<td><?= htmlentities($record['time']); ?></td>
			<td><?= htmlentities($record['remotehost']); ?></td>
			<td><?= htmlentities($record['transfertime']); ?></td>
			<td><?= htmlentities($record['msize']); ?></td>
			<td><?= htmlentities($record['state']); ?></td>
			<td><?= htmlentities($record['user']); ?></td>
			<td><?= htmlentities($record['name']); ?></td>
		</tr>
	<?php } ?>

</table>
