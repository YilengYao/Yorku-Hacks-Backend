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
		<?php resume_upload(); ?>
		<h1 class="text-center"><?php echo $_SESSION['username']; ?></h1>
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="myfile">
			<button name="resume_upload">Upload</button>
		</form>
	</div>
</div>

</div> <!--Container-->




	
</body>
</html>