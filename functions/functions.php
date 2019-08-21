<?php
    /* removes unwanted charaters such as > < etc. */
    function clean($string) {
        return htmlentities($string);
    }
    
    function redirect($location) {
        header("Location: {$location}"); 
    }

    function set_message($message) {
        if(!empty($message)) {
            $_SESSION['message'] = $message;
        } else {
            $message = "";
        }
    }

    function display_message() {
        if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    }

    function token_generator() {
        $token = md5(uniqid(mt_rand(), true));
    }

    /************validate_user_registration ******************/
    function valudate_user_registration() {
        $errors = [];
        $min = 5;
        $max = 254;

        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $first_name       = clean($_POST['first_name']);
            $last_name        = clean($_POST['last_name']);
            $username         = clean($_POST['username']);
            $email            = clean($_POST['email']);
            $password         = clean($_POST['password']);
            $confirm_password = clean($_POST['confirm_password']);

            if(strlen($first_name)) {

            }
        }

        if(strlen($first_name) < $min || strlen($first_name) > $max) {
            $errors[] = "Your first name cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($first_name)) {
            $errors[] = "Your first name cannot be empty";
        }

        if(strlen($last_name) < $min || strlen($last_name) < $max) {
            $errors[] = "Your last name cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($last_name)) {
            $errors[] = "Your last name cannot be empty";
        }

        if(strlen($username) < $min || strlen($username) < $max) {
            $errors[] = "Your username cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($username)) {
            $errors[] = "Your username cannot be empty";
        }

        if(strlen($email) < $min || strlen($email) < $max) {
            $errors[] = "Your email cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($email)) {
            $errors[] = "Your email cannot be empty";
        }

        if(strlen($password) < $min || strlen($password) < $max) {
            $errors[] = "Your password cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($password)) {
            $errors[] = "Your password cannot be empty";
        }
         
        if(strlen($confirm_password) < $min || strlen($confirm_password) < $max) {
            $errors[] = "Your confirm password cannot be less than {$min} or more than {$max} characters";
        }

        if(empty($confirm_password)) {
            $errors[] = "Your confirm password cannot be empty";
        }
        
        if(strcmp($password, $confirm_password) != 0) {
            $errors[] = "Password don't match";
        }

        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo error;
            }
        }
    }
?>