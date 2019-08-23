<!DOCTYPE html>
<html lang="en">


<?php include("includes/header.php"); ?>
<body>
<?php 
	if (!logged_in()) {
		redirect("login.php");
	}
?>

<?php include("includes/nav.php"); ?>




<div class="container">


	

	<div class="jumbotron">
		<h1 class="text-center"><?php echo $_SESSION['username']; ?></h1>

	</div>

</div> <!--Container-->




	
</body>
</html>