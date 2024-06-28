<?php
session_start();

/*This is real-time form validation via AJAX, checking inputs like username, email, password, confirm password, and captcha 
against some records and predefined criteria, returning validation results ("valid","invalid",and "exist") dynamically.*/

include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {//check if we get to this page by open("POST", "check.php", true) method if not then die.
    if ($_POST['type'] === "username") {//Check validity of username
        $user = $_POST['input'];
    
        $user = $_POST['input'];
        $sql="SELECT * FROM signup WHERE username='$user'";
        $result=mysqli_query($conn,$sql);

        if (strlen($user) > 4 && preg_match('/[a-zA-Z]/', $user)) {//check username length > 4 and contain at least one letter.
            if (mysqli_num_rows($result) != 0) {
                echo "exist"; // Username already exists
            }else
            echo "valid";
        }
        
        else {
            echo "invalid";
        }
    }
    
    elseif ($_POST['type'] === "email") {//Check validity of email.
        $email = $_POST['input'];
        $user = $_POST['input'];
        $sql = "SELECT * FROM signup WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
    
        // List of valid domains
        $validDomains = ['com', 'de', 'org', 'net', 'edu', 'gov'];
        $domainPattern = implode('|', $validDomains);
    
        // Check if email contains '@' and ends with one of the valid domains
        if (strpos($email, "@") !== false && preg_match("/\.(?:$domainPattern)$/i", $email)) {
            if (mysqli_num_rows($result) != 0) {
                echo "exist"; // Email already exists
            } else {
                echo "valid";
            }
        } else {
            echo "invalid";
        }
    }
    
    elseif ($_POST['type'] === "password") {//Check validity of password
        $password = $_POST['input'];

        if (strlen($password) >= 5 && preg_match('/[0-9]/', $password)&& preg_match('/[a-zA-Z]/', $password)) {//check password length > 5 and contain at least one letter.
            echo "valid";
        } else {
            echo "invalid";
        }
    } 
    elseif ($_POST['type'] === "confirmPassword") {//Check validity of confirm password
        $confirmPassword = $_POST['input'];
        $password = $_POST['password'];

        if ($confirmPassword == $password) {
            echo "valid";
        } else {
            echo "invalid";
        }
    }
}//this pracket should be last pracket
else{
    die();
}
?>
