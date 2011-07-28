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
<?php	else:	?>
			<?php print_r($SDK->get_hl($_GET['racer'])); ?>
<?php	endIf;	?>
	</body>
</html>
