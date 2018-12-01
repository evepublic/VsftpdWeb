<h1>FTP LOG</h1>

<?php if (isset($log_data['error'])) { ?>
	<div class="error">
		<?= $log_data['error'] ?>
	</div>
	<br>
<?php } ?>

<table class="log">

	<tr>
		<th>Info</th>
		<th>Size</th>
		<th>State</th>
		<th>User</th>
		<th>File Name</th>
	</tr>

	<?php if (!isset($log_data['error'])) foreach ($log_data as $record) { ?>
		<tr>
			<td><?= $record['info'] ?></td>
			<td><?= $record['msize'] ?></td>
			<td><?= $record['state'] ?></td>
			<td><?= $record['user'] ?></td>
			<td><?= $record['name'] ?></td>
		</tr>
	<?php } ?>

</table>
