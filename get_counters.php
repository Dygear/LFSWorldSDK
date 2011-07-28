<?php
	header('Content-Type: text/html; charset=UTF-8;');
	include('lfsworldsdk.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo (empty($_GET['type'])) ? 'Please Input Car or Tracks Short Name' : 'Page for ' . urlencode($_GET['type']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['type'])): ?>
		<form target="_SELF" method="GET">
			<label for="type">Car or Track:</label><input type="textbox" id="type" name="type" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else:
			$data = $SDK->get_counters($_GET['type']);
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