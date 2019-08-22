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

    function validation_errors($error_message) {
        return '
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Warning!</strong> ' . $error_message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        '; 
    }

    function success_message($success_message) {
        return '
        <div class="alert alert-success" role="alert">
            ' . $success_message . '
        </div>';
    }

    function row_exists($column, $value) {
        $sql = "SELECT * FROM users WHERE $column = '{$value}'";
        $result = query($sql);
        return row_count($result) > 0;
    }

    function send_email($email, $subject, $message, $headers) {
        return mail($email, $subject, $message, $headers);
    }

    function email_header($from) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $headers .= "From: {$from}" . "\r\n" .
        "Reply-To: {$from}" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        return $headers;
    }

    /************validate_user_registration ******************/
    function validate_user_registration() {
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

            if(strlen($first_name) > $max) {
                $errors[] = "Your first name cannot be more than {$max} characters";
            }
    
            if(empty($first_name)) {
                $errors[] = "Your first name cannot be empty";
            }
    
            if(strlen($last_name) > $max) {
                $errors[] = "Your last name cannot be more than {$max} characters";
            }
    
            if(empty($last_name)) {
                $errors[] = "Your last name cannot be empty";
            }
            
            if(row_exists('username', $username)) {
                $errors[] = "sorry the username: {$username} has already been registered";
            }

            if(strlen($username) < $min || strlen($username) > $max) {
                $errors[] = "Your username cannot be less than {$min} or more than {$max} characters";
            }
    
            if(empty($username)) {
                $errors[] = "Your username cannot be empty";
            }
            
            if(row_exists('email', $email)) {
                $errors[] = "Sorry the email: {$email} has already been registered";
            }

            if(strlen($email) < $min || strlen($email) > $max) {
                $errors[] = "Your email cannot be less than {$min} or more than {$max} characters";
            }
    
            if(empty($email)) {
                $errors[] = "Your email cannot be empty";
            }
    
            if(strlen($password) < $min || strlen($password) > $max) {
                $errors[] = "Your password cannot be less than {$min} or more than {$max} characters";
            }
    
            if(empty($password)) {
                $errors[] = "Your password cannot be empty";
            }
             
            if(strlen($confirm_password) < $min || strlen($confirm_password) > $max) {
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
                    echo validation_errors($error);                   
                }
            } else {
                if(register_user($first_name, $last_name, $username, $email, $password)){
                     set_message(success_message("Congratulations " . $username . " has been registered! Please check your email or spam forlder for the link to activate your account."));
                    redirect("login.php");
                } else {
                    // we need to handle this later this later
                    set_message(success_message("Sorry " . $username . " cannot be registered! Please email."));
                    redirect("login.php");
                }
            }
        }


    }

/**************************Register User *******************************/
    function register_user($first_name, $last_name, $username, $email, $password) {
        $first_name       = escape($_POST['first_name']);
        $last_name        = escape($_POST['last_name']);
        $username         = escape($_POST['username']);
        $email            = escape($_POST['email']);
        $password         = escape($_POST['password']);

        if(row_exists("email", $email) || row_exists("username", $username)) {
            return false;
        } else {
            $password   = md5($password);

            $validation_code = md5($username + microtime());
            
            $sql = "INSERT INTO users (first_name, last_name, username, email, password, validation_code, active) ";
            $sql .= "VALUES ('{$first_name}', '{$last_name}', '{$username}', '{$email}', '{$password}', '{$validation_code}',  0)";
            $result = query($sql);
            confirm($result);
            $subject = "Activate Account";
            $message = "Please click the link below to activate your Account
            
            http://yorkuhacks.ca/login/activate.php?email={$email}&code={$validation_code}
            ";


            $headers = email_header("noreply@yorkuhacks.ca");
            send_email($email, $subject, $message, $headers);
            return true;
        }

    }

/******************Activate User *************************/
function activate_user() {
    if($_SERVER['REQUEST_METHOD'] == "GET") {
        if(isset($_GET['email'])) {
            echo $email = clean($_GET['email']);
            echo $validation_code = clean($_GET['code']);
        }
    }
}
?>