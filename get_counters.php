<?php include('lfsworldsdk.php'); ?>
<html>
	<head>
		<title><?php echo (empty($_GET['type'])) ? 'Please Input Car or Tracks Short Name' : 'Page for ' . urlencode($_GET['type']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['type'])): ?>
		<form target="_SELF" method="GET">
			<label for="type">Car or Track:</label><input type="textbox" id="type" name="type" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else: 	?>
		<table>
			<tbody>
<?php		$data = $SDK->get_counters($_GET['type']);	?>
<?php		forEach ($data as $key => $val):	?>
				<tr>
					<th><?php echo $key; ?></th>
					<td><?php print_r($val); ?></td>
				</tr>
<?php		endForEach;	?>
<?php	endIf;	?>
			</tbody>
		</table>
	</body>
</html>