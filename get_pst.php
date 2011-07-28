<?php include('lfsworldsdk.php'); ?>
<html>
	<head>
		<title><?php echo (empty($_GET['racer'])) ? 'Please Input a Racer\'s Name' : 'Page for ' . urlencode($_GET['racer']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['racer'])): ?>
		<form target="_SELF" method="GET">
			<label for="racer">LFS Username:</label><input type="textbox" id="racer"  name="racer" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else: 	?>
		<table>
			<tbody>
<?php		$pst = $SDK->get_pst($_GET['racer']);
			forEach ($pst[0] as $key => $val):	?>
				<tr>
<?php			switch ($key):
					case 'distance':	?>
					<th>Meters Driven</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'fuel':	?>
					<th>Fuel burned in cl</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'laps':	?>
					<th>Laps Driven</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'joined':	?>
					<th>Hosts joined</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'win':	?>
					<th>Race wins</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'races_finished':	?>
					<th>Races finished</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'qual':	?>
					<th>Qualifications</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'pole':	?>
					<th>Pole positions</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'dragwins':	?>
					<th>Drags won</th>
					<td><?php echo $val; ?></td>
<?php				break;
					case 'ostatus':	?>
					<th>Online status</th>
					<td><?php
						switch ($val) {
							case 0: echo 'Offline'; break;
							case 1: echo 'Spectating'; break;
							case 2: echo 'In pits'; break;
							case 3: echo 'In race'; break;
						}
					?></td>
<?php				break;
					case 'hostname':	?>
					<th>Hostname</th>
					<td><?php echo LFSWorldSDK::convert_lfs_text($val); ?></td>
<?php				break;
					case 'last_time':	?>
					<th>Last active on</th>
					<td><?php echo date('M jS Y @ H:i', $val); ?></td>
<?php				break;
					case 'track':	?>
					<th>Track</th>
					<td><?php echo LFSWorldSDK::convert_track_name($val); ?></td>
<?php				break;
					default:
?>
					<th><?php echo ucwords($key); ?></th>
					<td><?php echo $val; ?></td>
<?php			endSwitch;	?>
				</tr>
<?php		endForEach;	?>
			</tbody>
		</table>
<?php	endIf;	?>
	</body>
</html>