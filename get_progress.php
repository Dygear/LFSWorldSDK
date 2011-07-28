<?php include('lfsworldsdk.php'); ?>
<html>
	<head>
		<title><?php echo (empty($_GET['host'])) ? 'Please Input a Host Name' : 'Page for ' . urlencode($_GET['host']); ?></title>
	</head>
	<body>
<?php	if (empty($_GET['host'])): ?>
		<form target="_SELF" method="GET">
			<label for="host">LFS Host Name:</label><input type="textbox" id="host" name="host" /><br />
			<input type="submit" value="Get Info" />
		</form>
<?php	else:
			$prog = $SDK->get_progress($_GET['host']);
			if (empty($prog)):	?>
		No Results Found
<?php		else:	?>
		<table>
			<tbody>
<?php			forEach ($prog as $key => $val):	?>
				<tr>
					<th><?php echo $key; ?></th>
					<td><?php print_r($val); ?></td>
				</tr>
<?php			endForEach;	?>
<?php	endIf;	?>
			</tbody>
		</table>
<?php		endIf;	?>
	</body>
</html>