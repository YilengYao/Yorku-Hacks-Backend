<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php") ?>

<body>

<?php include("includes/nav.php") ?>
	
<div class="container">


	<div class="jumbotron">
		<h1 class="text-center"> Home Page</h1>
	</div>

	<?php
		$sql = "SELECT * FROM users";
		$result = query($sql);

		confirm($result);

		$row = fetch_array($result);

		echo $row['username'];
	?>
	
</div> <!--Container-->




	
</body>
</html>