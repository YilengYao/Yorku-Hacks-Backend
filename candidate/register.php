<?php require_once("includes/header.php"); ?>


<?php 
// if($session->is_signed_in()) {

// redirect("index.php");

// }

// if(isset($_POST['submit'])) {

// $username = trim($_POST['username']);
// $password = trim($_POST['password']);




/// Method to check database user


// $user_found = User::verify_user($username, $password);



// if($user_found) {

// $session->login($user_found);
// redirect("index.php");


// } else {

// $the_message = "Your password or username are incorrect";


// }


// } else {
// $the_message = "";
// $username = "";
// $password = "";



// }


 ?>

<button onclick="location.href='login.php'" type="button">
         login</button>
<div class="col-md-4 col-md-offset-3">

<h4 class="bg-danger"><?php //echo $the_message; ?></h4>
	
<form id="login-id" action="" method="post">


<div class="form-group">
	<label for="username">Username</label>
	<input type="text" class="form-control" name="username" value="<?php //echo htmlentities($username); ?>" >

</div>

<div class="form-group">
	<label for="password">Password</label>
	<input type="password" class="form-control" name="password" value="<?php //echo htmlentities($password); ?>">
	
</div>

<div class="form-group">
	<label for="email">Email</label>
	<input type="email" class="form-control" name="email" value="<?php //echo htmlentities($email); ?>">
	
</div>

<div class="form-group">
	<label for="first_name">First Name</label>
	<input type="first_name"" class="form-control" name="first_name"" value="<?php //echo htmlentities($first_name"); ?>">
</div>

<div class="form-group">
	<label for="last_name">Last Name</label>
	<input type="last_name"" class="form-control" name="last_name"" value="<?php //echo htmlentities($last_name"); ?>">
</div>

<div class="form-group">
<input type="submit" name="submit" value="Submit" class="btn btn-primary">

</div>


</form>


</div>

 




