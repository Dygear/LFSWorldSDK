<?php
	header('Content-Type: text/html; charset=UTF-8;');
	include('lfsworldsdk.php');
	$cars = array (1 => 'XFG', 2 => 'XRG', 4 => 'XRT', 8 => 'RB4', 16 => 'FXO', 32 => 'LX4', 64 => 'LX6', 128 => 'MRT', 256 => 'UF1', 512 => 'RAC', 1024 => 'FZ5', 2048 => 'FOX', 4096 => 'XFR', 8192 => 'UFR', 16384 => 'FO8', 32768 => 'FXR', 65536 => 'XRR', 131072 => 'FZR', 262144 => 'BF1', 524288 => 'FBM', 1048576 => 'VWS');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>LFS Hosts Online</title>
		<style type="text/css">
			html, body {
				font: 12px Arial;
				background: #909090;
				margin: 1pt 1pt 1pt 1pt;
				padding: 0 0 0 0;
			}
			table, thead, tbody, tfoot, tr, th, td {
				border: 1px SOLID #000000;
				border-collapse: collapse;
			}
			table tbody td {
				text-align: center;
			}
			table {
				width: 100%;
			}
		</style>
	</head>
	<body>
		<table>
			<thead>
				<tr>
					<th rowspan="2">Name</th>
					<th rowspan="2">Lic.</th>
					<th rowspan="2">Track</th>
					<th rowspan="2">Rules</th>
					<th colspan="<?php echo count($cars); ?>">Cars</th>
					<th rowspan="2">Clients</th>
					<th rowspan="2">Ver</th>
				</tr>
				<tr>
<?php	foreach ($cars as $car): ?>
					<th><?php echo $car{0} . '<br />' . $car{1} . '<br />' . $car{2}; ?></th>
<?php	endForEach;	?>
				</tr>
			</thead>
			<tbody>
<?php	forEach ($SDK->get_hosts() as $host):	?>
<?php		if (count($host['racers']) > 0 ):	?>
				<tr>
					<th><?php echo LFSWorldSDK::convert_lfs_text($host['hostname']); ?></th>
					<td><?php if ($host['tmlt']['type'] == 0) echo 'Demo'; else echo 'S' . $host['tmlt']['type']; ?></td>
					<td><?php echo LFSWorldSDK::convert_track_name("{$host['tcrm']['track']}{$host['tcrm']['config']}{$host['tcrm']['reversed']}", TRUE); ?></td>
					<td><?php echo LFSWorldSDK::convert_flags_hlaps($host['rules'], TRUE); ?></td>
<?php			foreach ($cars as $id => $car): ?>
					<td><?php echo ($host['cars'] & $id) ? '×' : ''; ?></td>
<?php			endForEach;	?>
					<td><?php echo count($host['racers']) . '/' . $host['tcrm']['max']; ?></td>
					<td><?php echo "0.{$host['tmlt']['main']}{$host['tmlt']['letter']}{$host['tmlt']['testId']}"; ?></td>
				</tr>
<?php		endIf;	?>
<?php	endForEach; ?>
			</tbody>
		</table>
	</body>
</html>