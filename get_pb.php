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
<?php	else:
			$pb = $SDK->get_pb($_GET['racer']);
			$keys = array_keys($pb[0]);
?>
		<table>
			<thead>
				<tr>
					<th>ID</th>
<?php		forEach ($keys as $key):	?>
					<th><?php echo $key; ?></th>
<?php		endForEach;	?>
				</tr>
			</thead>
			<tbody>
<?php		forEach ($pb as $key => $val):	?>
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