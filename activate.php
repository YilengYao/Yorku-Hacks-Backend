<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.php"); ?>
<body>

<?php include("includes/nav.php"); ?>
	
<div class="container">




	<div class="jumbotron">
		<h1 class="text-center"><?php activate_user(); ?>
		
		</h1>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div  class="text-center">
						<a href="login.php">
							<button type="button" class="btn btn-success btn-lg">Login</button>
						</a>
						<a href="register.php" class="active" id="">
							<button type="button" class="btn btn-primary btn-lg">Register</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!--Container-->




	
</body>
</html>