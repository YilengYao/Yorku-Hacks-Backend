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
        $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        return $token;
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

    function error_message($error_message) {
        return '
        <div class="alert alert-danger" role="alert">
            ' . $error_message . '
        </div>';
    }

    function row_exists($column, $value) {
        $sql = "SELECT * FROM users WHERE $column = '{$value}'";
        $result = query($sql);
        return row_count($result) > 0;
    }

    function user_exists($email, $validation_code) {
        $sql = "SELECT * FROM users WHERE email = '{$email}' AND validation_code = '{$validation_code}'";
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
                     set_message(success_message("Congratulations " . $username . " has been registered! Please check your email or spam folder for the link to activate your account."));
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
            
            $sql = "INSERT INTO users (first_name, last_name, username, email, password, validation_code, active, number_submissions) ";
            $sql .= "VALUES ('{$first_name}', '{$last_name}', '{$username}', '{$email}', '{$password}', '{$validation_code}',  0, 0)";
            $result = query($sql);
            confirm($result);
            $subject = "Activate Account";
            $message = "Please click the link below to activate your Account
            
            http://localhost/yorkuhacks/activate.php?email={$email}&code={$validation_code}
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
            $email = clean($_GET['email']);
            $validation_code = clean($_GET['code']);
            
            if(user_exists(escape($email), escape($validation_code))) {
                $email = escape($email);
                $validation_code = escape($validation_code);
            $sql_validate = "UPDATE users SET active = 1 WHERE email = '{$email}' AND validation_code = '{$validation_code}'";
                $result = query($sql_validate);
                confirm($result);
                set_message(success_message("Congratulations $email has been activated please login."));
                redirect("login.php");
            } else {
                set_message(error_message("Sorry! Your account could not be activated."));
            }
        } 
    }
   redirect("login.php");
}

/*****************Validate User Login****************************/

    function validate_user_login() {
        $min = 5;
        $max = 254;
        $errors = [];
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $email            = clean($_POST['email']);
            $password         = clean($_POST['password']);

            if(strlen($email) < $min || strlen($email) > $max) {
                $errors[] = "Your email cannot be less than {$min} or more than {$max} characters";
            }
        
            if(empty($email)) {
                $errors[] = "Email field cannot be empty";
            }
        
            if(strlen($password) < $min || strlen($password) > $max) {
                $errors[] = "Your password cannot be less than {$min} or more than {$max} characters";
            }
        
            if(empty($password)) {
                $errors[] = "Password field cannot be empty";
            }
        
            if(!empty($errors)) {
                foreach($errors as $error) {
                    echo Validation_errors($error);
                }
            } else {
                if(login_user($email, $password)) {
                    redirect('candidate.php');
                } else {
                    echo validation_errors("Your email or password are not correct.");
                }
            }
        }


    }


/**************User Login Functions *********************/
    function login_user($email, $password) {
        $email = escape($email);
        $password = escape($password);
        $sql = "SELECT * FROM users WHERE email = '{$email}' AND active = 1";
        $result = query($sql);

        if(row_count($result) == 1) {
            $row = fetch_array($result);
            $db_password = $row['password'];
            if(md5($password) === $db_password) {  
                /* To keep our user logged in and identify the user */
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $row['username'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

/*********************Persistent login function ************************/
    function logged_in() {
        if(isset($_SESSION['email'])) {
            return true;
        } else {

            return false;
        }
    }

    function logged_out() {
        session_destroy();
    }

/****************Recover Password *************/
    function recover_password() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            /* return back to login page if you don't want to recover password */
            if(isset($_POST['cancel-submit'])) {
                redirect('login.php');
            }



            /* send password reset email */
            if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {
                $email = clean($_POST['email']);

                if(row_exists("email", $email)) {
                    $email = escape($email);
    
                    $validation_code = md5($email + microtime());
                    $validation_code = escape($validation_code);
                    setcookie('temp_access_code', $validation_code, time() + 600);
                    $sql = "UPDATE users SET validation_code = '{$validation_code}' WHERE email = '{$email}'";
                    $result = query($sql);
                    confirm($result);
                    $subject = "Pleaser reset your password";
                    $message = " Here is your password reset {$validation_code}

                    Click here to reset your password http://localhost/yorkuhacks/reset.php?email={$email}&code={$validation_code}
                    
                    You validation code is only valid for 10 minutes.";
                    $header = "From: noreply@yorkuhacks.ca";
                    if(send_email($email, $subject, $message, $header)) {
                        echo success_message("Please check your email or spam folder for the link to reset your password.");
                        redirect("login.php");
                    } else {
                        echo validation_errors("Email could not be sent ");
                    }
                } else {
                    echo validation_errors("The user with this email does not exist.");
                }
            } else {
                redirect('login.php');
            }
        }
    }

/******************* Reset Password **********************/
    function reset_password() {
        $min = 5;
        $max = 254;
        if(!isset($_COOKIE['temp_access_code'])) {
            echo error_message("Sorry your validation token has expired");
           redirect('recover.php');
        }
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            if($_POST["password"] != $_POST["confirm_password"]) {
                echo error_message("Password and Confirm Password don't match.");
            } elseif (strlen($_POST["password"]) < $min || strlen($_POST["password"]) > $max) {
                echo error_message("Your password cannot be less than {$min} or more than {$max} characters");
            } else {
                if(isset($_POST['reset-password-submit'])) {
                    if(isset($_GET['email']) && isset($_GET['code'])) {
                        $validation_code = escape(clean($_GET['code']));
                        $email = escape(clean($_GET['email']));
                    $sql = "SELECT validation_code FROM users WHERE email = '{$email}' AND validation_code = '{$validation_code}'";
                        $result = query($sql);
                        if(row_count($result) == 1) {
                            confirm($result);
                            $row = fetch_array($result);

                            if($row['validation_code'] == $validation_code && $row['validation_code'] == $_POST['form_validation_code']) {
                                $password = escape(clean(md5($_POST["password"])));
                                $sql = "UPDATE users SET password = '{$password}' WHERE email = '{$email}' AND validation_code = '{$validation_code}'";
                                $result = query($sql);
                                echo success_message("You have successfully reset your password.");
                                //redirect('login.php');
                            }
                        } else {
                            redirect('login.php');
                        }
                    }
                }
            }
        }
    }
?>