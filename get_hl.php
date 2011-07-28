<?php
	header('Content-Type: text/html; charset=UTF-8;');
	include('lfsworldsdk.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo (empty($_GET['racer'])) ? 'Please Input a Racer\'s Name' : 'Page for ' . urlencode($_GET['racer']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['racer'])): ?>
		<form target="_SELF" method="GET">
			<label for="racer">LFS Username:</label><input type="textbox" id="racer" name="racer" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else:
			$data = $SDK->get_hl($_GET['racer']);
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
<?php		endForEach;
		endIf;	?>
			</tbody>
		</table>
	</body>
</html>