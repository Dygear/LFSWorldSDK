<?php
	header('Content-Type: text/html; charset=UTF-8;');
	include('lfsworldsdk.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo (empty($_GET['track']) || empty($_GET['car'])) ? 'Please Input a Track & Car Combo' : 'Page for ' . urlencode($_GET['track']) . urlencode($_GET['car']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['track']) || empty($_GET['car'])): ?>
		<form target="_SELF" method="GET">
			<label for="track">Track:</label><input type="textbox" id="track" name="track" /><br />
			<label for="car">Car:</label><input type="textbox" id="car" name="car" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else:
			$data = $SDK->get_ch($_GET['track'], $_GET['car']);
			$keys = array_keys($data[0]);
?>
		<table>
			<thead>
				<tr>
					<th>KEY</th>
<?php		forEach ($keys as $key):	?>
					<th><?php echo $key; ?></th>
<?php		endForEach;	?>
				</tr>
			</thead>
			<tbody>
<?php		forEach ($data as $key => $val):	?>
				<tr>
					<th><?php echo $key; ?></th>
<?php			forEach ($keys as $k):
					switch ($k):
						case 'track':	?>
					<td><?php echo LFSWorldSDK::convert_track_name($val[$k], TRUE); ?></td>
<?php					break;
						case 'split1':
						case 'split2':
						case 'split3':
						case 'laptime': ?>
					<td><?php echo LFSWorldSDK::convert_lfsw_time($val[$k]); ?></td>
<?php					break;
						case 'flags_hlaps': ?>
					<td><?php foreach ($val[$k] as $flag) echo $flag; ?></td>
<?php					break;
						default:	?>
					<td><?php echo $val[$k]; ?></td>
<?php				endSwitch;
				endForEach;	?>
				</tr>
<?php		endForEach;	?>
			</tbody>
		</table>
<?php	endIf;	?>
	</body>
</html>