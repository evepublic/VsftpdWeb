<div class="grid_16">
	<h1>Service monitor</h1>

	<table class="monitor">
		<?php if (isset($mon1['name']) and $mon1['name'] === 'listener') { ?>
			<tr>
				<th class="success" colspan="5">VSFTPD server is online</th>
			</tr>
		<?php } else { ?>
			<tr>
				<th class="error" colspan="5">VSFTPD server is offline</th>
			</tr>
		<?php } ?>
	</table>

	<table class="monitor">
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
						<?= $child2['user'] ?>
					</td>

					<td>
						<?php switch ($child2['command']) {
							case 'IDLE':
								echo 'Idle';
								break;
							case 'STOR':
								echo 'Uploading file: ' . $child2['parameter'];
								break;
							case 'RETR':
								echo 'Downloading file: ' . $child2['parameter'];
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
						<?= $child2['command'] . ' ' . $child2['parameter'] ?>
					</td>

				</tr>

				<?php
			}
		}
		?>

	</table>
</div>

<br/>
<br/>

<div class="grid_16">
	<h1>Users Connected</h1>

	<table class="monitor">
		<?php foreach ($mon2 as $line) {
			$line = substr($line, 0, -16);
			$user = strstr($line, "vsftpd", true);
			echo "<tr><td id = inf>    <strong>$user</strong> is logged in </td><td>U: $line </td></tr>";
		} ?>
	</table>
</div>
