<?php
session_start();

//Signup page where user fills his information and create a new account.

require 'sendEmail.php';
include 'connect.php';

if (isset($_SESSION['start_time'])) {
  $currentTime = time();
  $elapsedTime = $currentTime - $_SESSION['start_time'];
  // If timer hasn't expired, redirect to verify.php
  if ($elapsedTime < 60) {
    header("refresh:3;url=verify.php");
    die('Please wait until the time ends.');
  } 
  else {
    // Timer expired, destroy session and allow user to sign up again
    if (isset($_SESSION['user']) || isset($_SESSION['email'])) {
    $SaveUser=$_SESSION['user'];// save user session if exit in a variable to put the value in the session again after destroy
    $SaveEmail=$_SESSION['email'];
}
    session_destroy();
    $_SESSION['user']=$SaveUser;
    $_SESSION['email']=$SaveEmail;
  }
}

if (!isset($_SESSION['user']) || !isset($_SESSION['email'])) {
    $_SESSION['user'] = "";
    $_SESSION['email'] = "";
}

$captcha = str_pad(rand(11111, 99999), 5, '0', STR_PAD_LEFT);// Generate a verification code and store it in session
$_SESSION['captcha'] = $captcha;

if (isset($_POST['signup'])) {
  $user = addslashes($_POST['user']);//addslashes prevent sql injection
  $_SESSION['user'] = $user;
  $email = addslashes($_POST['email']);
  $_SESSION['email'] = $email;
  $password = addslashes($_POST['password']);
  $hashedpass = substr(hash("sha256", $password), 0, 30);
  $_SESSION['pass'] = $hashedpass;
  
  $verfCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);// Generate a verification code and store it in session
  $_SESSION['Signup_Verificationcode'] = $verfCode;

    $recaptchaSecret = 'secrete_key ^_^';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaURL = 'https://www.google.com/recaptcha/api/siteverify';
    
    $recaptchaResponseData = file_get_contents($recaptchaURL . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    
    if ($recaptchaResponseData !== false) {
        $recaptchaResponseData = json_decode($recaptchaResponseData);
        
        if ($recaptchaResponseData !== null && $recaptchaResponseData->success) {
        } else {
            echo '<div class="alert alert-danger" role="alert">Captcha validation failed, please try again.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Captcha validation request failed, please try again.</div>';
    }
    
  // Check if the username or email already exists in the database
  $sql = "select * from signup where username='$user' or email='$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) != 0) {
    echo '<div class="alert alert-danger" role="alert">Username or email already exists</div>';
  } else {// Send verification email
    $subject = "Register Account";
    $message="Your verification code is: <span id='verfCode' style='cursor: pointer; color: blue;'>$verfCode</span>";
    sendEmail($email,$subject,$message);
  }
}
?>
<html>
<head>
    <title>SignUp</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="signupStyle.css">
</head>
<body>
    <img src="images/signup.jpg" class="signupimg">
    <div class="container">
        <div class="text-center mb-4">
        <p class="welcome" >Welcome to my website ^_^</p>
        </div>
        <div class="signup card p-4">
            <form id="signupForm" action="signup.php" method="post">
                <div class="form-group">
                    <label for="user">Username:</label>
                    <input type="text" class="form-control" name="user" id="user" placeholder="username" value="<?php echo $_SESSION['user']; ?>" oninput="check(this.value,'username'); validateForm();" required>
                    <p class="errorMessage" id="UserErrorMessage"></p>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="123@gmail.com" value="<?php echo $_SESSION['email']; ?>" oninput="check(this.value,'email'); validateForm();" required>
                    <p class="errorMessage" id="emailErrorMessage"></p>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" name="password" id="password" oninput="check(this.value,'password'); validateForm();" required>
                        <img id="eyeIcon" class="eye-icon1" src="images/closedeye.jpg" style="width:8%"></img>
                    </div>
                    <p class="errorMessage" id="passErrorMessage"></p>
                </div>
                <div class="form-group">
                    <label for="confirmPass">Confirm Password:</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" name="confirmPass" id="confirmPass" onpaste="return false;" oninput="check(this.value,'confirmPassword'); validateForm();" required>
                        <img id="eyeIcon2" class="eye-icon2" src="images/closedeye.jpg" style="width:8%"></img>
                    </div>
                    <p class="errorMessage" id="confirmPassErrorMessage"></p>
                </div>
                    <div class="form-group">
                      <label for="captcha">Solve captcha:</label>
                        <div class="g-recaptcha" data-sitekey="6LfoEAMqAAAAAMz8rCWg8FzHdmMOGFjJzJ-hrMnl" data-callback="captchaVerified"></div>
                         <input type="hidden" id="captcha-response" name="captcha-response">
                     </div>
                <button type="submit" class="btn btn-primary" id="signupButton" name="signup" disabled>SignUp</button>
                <a href="login.php" class="btn btn-link">Already have an account?</a>
            </form>
        </div>
        <a href="home.php" style="color:white;margin-left:10%;">Skip this step?</a>
    </div>
    <script>
        var usernameExists = false;
        var emailExists = false;

        function check(input, type) {//Ajax for error messages
            var x = new XMLHttpRequest();
            x.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText === "invalid") {//if check.php returns invalid
                        if (type === "username") {
                            document.getElementById("UserErrorMessage").innerHTML = "Username must be at least 4 characters long and contain at least one letter";
                        } else if (type === "email") {
                            document.getElementById("emailErrorMessage").innerHTML = "Email must contain @ and end with .com";
                        } else if (type === "password") {
                            document.getElementById("passErrorMessage").innerHTML = "Password must be at least 5 characters length and contain at least a letter and a number";
                        } else if (type === "confirmPassword") {
                            document.getElementById("confirmPassErrorMessage").innerHTML = "Passwords do not match";
                        }
                    } else if (this.responseText === "exist") {//if check.php returns exist
                        if (type === "username") {
                            document.getElementById("UserErrorMessage").innerHTML = "Username already exists";
                            usernameExists = true;
                        } else {
                            document.getElementById("emailErrorMessage").innerHTML = "Email already exists";
                            emailExists = true;
                        }
                    } else {//if check.php returns valid
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
                    validateForm();
                }
            };

            var formData = new FormData();
            formData.append('input', input);
            formData.append('type', type);

            if (type === "confirmPassword") {
                var password = document.getElementsByName("password")[0].value;
                formData.append('password', password);
            }

            x.open("POST", "check.php", true);
            x.send(formData);
        }

        function captchaVerified(response) {
        document.getElementById("captcha-response").value = response;
        captchaFilled = true;
        validateForm();
    }

    function validateForm() {
    var user = document.getElementById("user").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPass = document.getElementById("confirmPass").value;
    var captcha = document.getElementById("captcha-response").value;

    // List of valid domains
    var validDomains = ['com', 'de', 'org', 'net', 'edu', 'gov'];
    var emailIsValid = email.includes("@") && validDomains.some(function(domain) {
        return email.endsWith("." + domain);
    });

    var isValid = (user.length > 4) &&
                  emailIsValid &&
                  (password.length >= 5 && /\d/.test(password) && /[a-zA-Z]/.test(password)) &&
                  (password === confirmPass) &&
                  captcha && // Assuming captchaFilled is equivalent to checking if captcha is filled
                  !usernameExists &&
                  !emailExists;

    document.getElementById("signupButton").disabled = !isValid;
}


    document.getElementById("eyeIcon").addEventListener("click", function() {
        var passwordInput = document.getElementById("password");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.src = "images/opendeye.jpg";
        } else {
            passwordInput.type = "password";
            eyeIcon.src = "images/closedeye.jpg";
        }
    });

    document.getElementById("eyeIcon2").addEventListener("click", function() {
        var passwordInput = document.getElementById("confirmPass");
        var eyeIcon2 = document.getElementById("eyeIcon2");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon2.src = "images/opendeye.jpg";
        } else {
            passwordInput.type = "password";
            eyeIcon2.src = "images/closedeye.jpg";
        }
    });

    document.getElementById("signupForm").addEventListener("submit", function(event) {
        validateForm();
        if (document.getElementById("signupButton").disabled) {
            event.preventDefault();
        }
    });

    window.onload = function() {
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    };
</script>
</body>
</html>