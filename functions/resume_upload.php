<?php
    $resume_con = mysqli_connect('localhost', 'root', '', 'resume_db'); 

    function resume_row_count($result) {
        return mysqli_num_rows($result);
    }

    function resume_escape($string) {
        global $resume_con;
        return mysqli_real_escape_string($resume_con, $string);
    }

    function resume_query($sql) {
        global $resume_con;
        return mysqli_query($resume_con, $sql);
    }

    function resume_confirm($result){
        global $resume_con;
        if(!$result) { die("Query Failed" . mysqli_error($con)); }
    }

    function resume_fetch_array($result) {
        global $resume_con;
        return mysqli_fetch_array($result);
    }

    function resume_upload() {
        global $resume_con;
        // foreach($_POST as $key => $value) {
        //     echo "$key => $value <br>";
        // }
        if(isset($_POST['resume_upload'])) {
            foreach($_FILES['myfile'] as $key => $value) {
                echo "$key => $value <br>";
            }
            $type = resume_escape(clean($_FILES['myfile']['type']));
            $file_location = resume_escape(clean($_FILES['myfile']['tmp_name']));
            $file_name = resume_escape(clean($_FILES['myfile']['name']));
            $data = file_get_contents($file_location);
            $email = resume_escape(clean($_SESSION['email']));
            // // $stmt = $resume_con->prepare("INSERT INTO resume (data) VALUES( ?)");
            // // $null = NULL;
            // // $stmt->bind_param("b", $null);
            // // $stmt->send_long_data(0, file_get_contents($file_location));
            // // $stmt->execute();
            // mysqli_query($resume_con, "INSERT INTO resume (name, data) VALUE ('email', '$data')");

            $dbh = new PDO("mysql:host=localhost;dbname=resume_db", "root", "" );
            $stmt = $dbh->prepare("INSERT INTO resume VALUES ('', ?, ?, ?)");
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $type);
            $stmt->bindParam(3, $data);
            $stmt->execute();
        }
    }
?>