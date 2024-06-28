<?php
session_start();

//Settings page where user can change email,username,password and can delete his account.


require 'sendEmail.php';
include 'connect.php';

if(!isset($_SESSION['checkLogin']) && !isset($_COOKIE['username'])){//this session and cookies are created when user login.
     header("refresh:4;url=login.php");
    die("Please login to your account first to open this page");
}

if (isset($_SESSION['start_time'])) {//if a verification code requested user not allowed to came back to this page and send another request until time count down ends
    $currentTime = time();
    $elapsedTime = $currentTime - $_SESSION['start_time'];
    // If timer hasn't expired, redirect to verify.php
    if ($elapsedTime < 60) {
      header("refresh:3;url=verify.php");
      die('Please wait until the time ends.');
    } 
    else {
        // Timer expired, destroy session and allow user to sign up again
        if(isset($_SESSION['checkLogin']) || isset($_SESSION['email'])){
            $SaveCheck=$_SESSION['checkLogin'];
            $SaveEmail=$_SESSION['email'];
        }
        session_destroy();
        $_SESSION['checkLogin']=$SaveCheck;
        $_SESSION['verfEmail']=$SaveEmail;
      }
  }

if(isset($_SESSION['checkLogin']) || isset($_COOKIE['username']) || isset($_SESSION['verfEmail'])) {//Put the username session value in a variable $user
    if(isset($_SESSION['checkLogin'])){
    $user=$_SESSION['checkLogin'];
    }
    if(!isset($_SESSION['checkLogin'])&&isset($_COOKIE['username'])){
    $user=$_COOKIE['username'];
    }
}
$query="select email from signup where username='$user'";//Get the email of this account from the database
$getEmail=mysqli_query($conn,$query);
$row=mysqli_fetch_array($getEmail);
$email=$row["email"];

if(isset($_POST["changeEmail"])){//Change the email
    $new_email=addslashes($_POST["new_email"]);

    if($email==$new_email){
        echo "The email is already seted to $email";
    }
    session_destroy();
    session_start();
    $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $_SESSION['verfEmail']=$code;
    $_SESSION["old_email"]=$email;
    $_SESSION["new_email"]=$new_email;
    $_SESSION['checkLogin']=$user;
    if(isset($_COOKIE["username"])){
        $name=$_COOKIE["username"];
        $expire=time()+(60*60*24*30);
        setcookie('name', $user, $expire);
        setcookie('username', '', time() - 3600);
    }
    $subject = "Change the email of your account";
    $message="Use this verification code to set this email as and email for your account: <span id='verfCode' style='cursor: pointer; color: blue;'>$code</span>";
    sendEmail($new_email,$subject,$message);
    header("refresh:4;url=settings.php");

}
if(isset($_POST["changeUser"])){//Change username
    $newuser=addslashes($_POST["new_username"]);
    if($user==$newuser){
        echo"username is already set to $newuser";
        header("refresh:4;url=settings.php");
    }
    else{
    $sql="update signup set username='$newuser' where username='$user'";
    $result = mysqli_query($conn, $sql);
    echo"username changed successfuly";
    $_SESSION['checkLogin']=$newuser;
    if(isset($_COOKIE['username'])){
     $expire=time()+(60*60*24*30);
     setcookie('username', $newuser, $expire);
    //  setcookie('username', $email, $expire);
    }
    header("refresh:4;url=settings.php");
}
}
if(isset($_POST["change_password"])){//Change password

    $new_password=substr(hash("sha256", addslashes($_POST["new_password"])), 0, 30);
    $confirm_password=substr(hash("sha256", addslashes($_POST["confirm_password"])), 0, 30);
    $current_pass=substr(hash("sha256", addslashes($_POST["current_password"])), 0, 30);
    $query="select password from signup where username='$user'";
    $result=mysqli_query($conn,$query);
    if(mysqli_num_rows($result)==1){
        $row_pass=mysqli_fetch_array($result);
        $old_password=$row_pass['password'];
    }
    if($current_pass==$old_password){
        if($new_password==$confirm_password){
            $set_new_pass="update signup set password='$new_password' where username='$user'";
            $result_new_pass = mysqli_query($conn, $set_new_pass);
            echo "Password changed successfuly";
            $subject = "Password Changed";
            $message="The password of your account has been changed";
            sendEmail($email,$subject,$message);
            header("refresh:4;url=settings.php");
        }
    }
    else{
        echo"The current password you entered is incorrect";
    }
}

if(isset($_POST["forget_password"])){//Forget Password.
     $code=str_pad(rand(111111, 999999), 6, '0', STR_PAD_LEFT);
     $_SESSION['verfC']=$code;
     $_SESSION['EmailForget']=$email;
     $subject = "Forget Password";
     $message="Your verification code is: <span id='verfCode' style='cursor: pointer; color: blue;'>$code</span>";
     sendEmail($email,$subject,$message);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .settings-container {
            margin-top: 50px;
        }
        .settings-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        .menu-toggle {
      display: block;
      width: 30px;
      height: 20px;
      position: relative;
      cursor: pointer;
      margin-top: 2%;
      margin-left: 95%;
    }

    .bar {
      width: 30px;
      height: 3px;
      background-color: black;
      margin: 6px 0;
      transition: 0.4s;
    }

    .change .bar:nth-child(1) {
      transform: rotate(-45deg) translate(-5px, 6px);
    }

    .change .bar:nth-child(2) {
      opacity: 0;
    }

    .change .bar:nth-child(3) {
      transform: rotate(45deg) translate(-5px, -6px);
    }

    .menu-content {
    display: none;
    position: absolute;
    top: 60px;
    right: 20px;
    background-color: white;
    border: 1px solid #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    padding: 10px;
    border-radius: 5px;
    color: black;
    font-family: Arial, sans-serif;
    font-size: 14px;
    z-index: 1000; /* Ensure the menu is above other elements */
}

.menu-content.show {
  display: block;
}

.menu-content a {
  display: block;
  margin-bottom: 10px;
  text-decoration: none;
  color: black;
  padding: 5px 10px; /* Add padding for spacing */
  border-bottom: 1px solid black; /* Add white border between list items */
  transition: color 0.3s ease;
}

.menu-content a:last-child {
  margin-bottom: 0; /* Remove margin from the last list item */
}

.menu-content a:hover {
  color: #ffd700; /* Change the color on hover */
}
    </style>
</head>
<body>
<div class="menu-toggle" id="menuIcon">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
  <div class="menu-content" id="menuContent">
  <a href="home.php">Home</a>
    <a href="skills.php">Skills</a>
    <a href="contact.php">Contact</a>
    <a href="logout.php">Logout</a>
  </div>
    <div class="container settings-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="settings-form">
                    <h2 class="form-header">Settings</h2>
                    <!-- Change Email Form -->
                    <form id="EmailForm" action="settings.php" method="post">
                        <h4>Change Email</h4><br>
                        <p>current email: <?php echo $email;?></p><br>
                        <div class="form-group">
                            <label for="newEmail">New Email</label>
                            <input type="email" class="form-control" id="newEmail" name="new_email" oninput="check(this.value,'email'); emailvalidateForm();" required>
                            <p class="errorMessage" id="emailErrorMessage"></p>
                        </div>
                        <button type="submit" name="changeEmail" id="changeEmail" class="btn btn-primary">Update Email</button>
                    </form>
                    <hr>
                    <!-- Change Username Form -->
                    <form id="UsernameForm" action="settings.php" method="post">
                        <h4>Change Username</h4>
                        <div class="form-group">
                            <label for="newUsername">New Username</label>
                            <input type="text" class="form-control" id="newUsername" name="new_username" oninput="check(this.value,'username'); userValidateForm();" required>
                            <p style="color:red;font-size:70%" id="UserErrorMessage"></p>
                        </div>
                        <button type="submit" id="changeUsername" name="changeUser" class="btn btn-primary">Update Username</button>
                    </form>
                    <hr>
                    <!-- Change Password Form -->
                    <form id="PasswordForm" action="settings.php" method="post">
                        <h4>Change Password</h4>
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" oninput="check(this.value,'password'); passValidateForm();" required>
                            <p style="color:red;font-size:70%;"id="passErrorMessage"></p>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" onpaste="return false;" oninput="check(this.value,'confirmPassword'); passValidateForm();" required>
                            <p style="color:red;font-size:70%;" id="confirmPassErrorMessage"></p>
                        </div>
                        <button type="submit" id="changePassword" name="change_password" class="btn btn-primary">Update Password</button>
                    </form>
                    <hr>
                    <form action="settings.php" method="post">
                    <div class="forgot-password">
                    <button type="submit" id="changePassword" name="forget_password" class="btn btn-primary">Forget Password?</button>
                    </div>
                    </form>
                    <!-- Delete Account Form -->
                    <form id="deleteAccountForm" action="deleteAccount.php" method="post">
                        <h4>Delete Account</h4>
                        <button type="button" class="btn btn-danger" id="deleteAccountBtn">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var usernameExists = false;
        var emailExists = false;

        document.getElementById('deleteAccountBtn').addEventListener('click', function () {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                document.getElementById('deleteAccountForm').submit();
            }
        });
        document.getElementById("menuIcon").addEventListener("click", function () {
            this.classList.toggle("change");
            document.getElementById("menuContent").classList.toggle("show");
        });

        function check(input, type) {
            var x = new XMLHttpRequest();
            x.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText === "invalid") {
                        if (type === "username") {
                            document.getElementById("UserErrorMessage").innerHTML = "Username must be greater than 4 characters";
                        } else if (type === "email") {
                            document.getElementById("emailErrorMessage").innerHTML = "Email must contain @ and end with .com";
                        } else if (type === "password") {
                            document.getElementById("passErrorMessage").innerHTML = "Password must be at least 5 characters length and contain at least a letter and a number";
                        } else if (type === "confirmPassword") {
                            document.getElementById("confirmPassErrorMessage").innerHTML = "Passwords do not match";
                        }
                    } else if (this.responseText === "exist") {
                        if (type === "username") {
                            document.getElementById("UserErrorMessage").innerHTML = "Username already exists";
                            usernameExists = true;
                        } else {
                            document.getElementById("emailErrorMessage").innerHTML = "Email already exists";
                            emailExists = true;
                        }
                    } else {
                        if (type === "username") {
                            document.getElementById("UserErrorMessage").innerHTML = "";
                            usernameExists = false;
                        } else if (type === "email") {
                            document.getElementById("emailErrorMessage").innerHTML = "";
                            emailExists = false;
                        } else if (type === "password") {
                            document.getElementById("passErrorMessage").innerHTML = "";
                        } else if (type === "confirmPassword") {
                            document.getElementById("confirmPassErrorMessage").innerHTML = "";
                        }
                    }
                    validateForm(type);
                }
            };

            var formData = new FormData();
            formData.append('input', input);
            formData.append('type', type);

            // For confirmPassword, also send the password value for comparison
            if (type === "confirmPassword") {
                var password = document.getElementsByName("new_password")[0].value;
                formData.append('password', password);
            }

            x.open("POST", "check.php", true);
            x.send(formData);
        }


        //Enable buttons if they met conditions
        function validateForm(type) {
            if (type === 'username') {
                userValidateForm();
            } else if (type === 'email') {
                emailvalidateForm();
            } else if (type === 'password' || type === 'confirmPassword') {
                passValidateForm();
            }
        }

        function passValidateForm() {
        var password = document.getElementById("newPassword").value;
        var confirmPass = document.getElementById("confirmPassword").value;

        var isValid = (password.length >= 5 && /\d/.test(password) && /[a-zA-Z]/.test(password)) && (password === confirmPass);

        document.getElementById("changePassword").disabled = !isValid;
    }

    function userValidateForm() {
        var username = document.getElementById("newUsername").value;

        var isValid = (username.length > 4); // Add additional validation logic as needed

        document.getElementById("changeUsername").disabled = !isValid;
    }

    function emailvalidateForm() {
        var email = document.getElementById("newEmail").value;

        var isValid = (email.includes("@") && email.endsWith(".com")); // Add additional validation logic as needed

        document.getElementById("changeEmail").disabled = !isValid;
    }

    document.getElementById("UsernameForm").addEventListener("submit", function(event) {
        userValidateForm();
        if (document.getElementById("changeUsername").disabled) {
            event.preventDefault();
        }
    });

    document.getElementById("EmailForm").addEventListener("submit", function(event) {
        emailvalidateForm();
        if (document.getElementById("changeEmail").disabled) {
            event.preventDefault();
        }
    });

    document.getElementById("PasswordForm").addEventListener("submit", function(event) {
        passValidateForm();
        if (document.getElementById("changePassword").disabled) {
            event.preventDefault();
        }
    });
</script>
</body>
</html>
