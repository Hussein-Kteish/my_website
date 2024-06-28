<?php
session_start();

//This page take the verification code sent to the user on email as input and check if it is correct.

include 'connect.php';
// If verification code is not set or timer has expired, redirect
if (!isset($_SESSION['Signup_Verificationcode']) && !isset($_SESSION['Password_Verification']) && !isset($_SESSION['verfEmail'])) {// if their is no verifcation request this page must not open
    die();
}

// Check if timer has expired
if(!isset($_SESSION['start_time'])){// so that not every time we refresh verify.php excute this line
$_SESSION['start_time'] = time();
}

// Handle form submission
if (isset($_POST['verify'])) {
  $code = $_POST['code'];

  if (isset($_SESSION['Signup_Verificationcode'])) {//Signup case.
    if ($code == $_SESSION['Signup_Verificationcode']) {
      // Verification successful
      $user = $_SESSION['user'];
      $email = $_SESSION['email'];
      $hashedpass = $_SESSION['pass'];
      $VerfCode = $_SESSION['Signup_Verificationcode'];
      $add = "insert into signup values('$user','$email','$hashedpass')";
      $added = mysqli_query($conn, $add);
      if (!$added) {
        echo '<span style="color: white; margin-top:50px;">error</span>';
      } else {
        session_destroy();
        header("location:login.php");
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Incorrect code</div>';
            }
        } else if (isset($_SESSION['Password_Verification'])) {//Forget password and change password case.
            if ($code == $_SESSION['Password_Verification']) {
                header("location:changePassword.php");
            } else {
                echo '<div class="alert alert-danger" role="alert">Incorrect code</div>';
            }
        }
        else if(isset($_SESSION['verfEmail'])){//Change email case.
            if($code==$_SESSION['verfEmail']){
                    if(!isset($_SESSION['checkLogin'])&&isset($_COOKIE['username'])){
                    $user=$_COOKIE['username'];
                    $old_email=$_COOKIE['email'];
                    }
                    else{
                        $old_email=$_SESSION['old_email'];
                    }
                $new_email=$_SESSION['new_email'];
                $user=$_SESSION['checkLogin'];
                $query="update signup set email='$new_email' where email='$old_email'";
                $change_email=mysqli_query($conn,$query);
                echo "Email changed successfuly";
                session_destroy();
                session_start();
                $_SESSION['email']=$new_email;
                $_SESSION['checkLogin']=$user;
                $expire=time()+(60*60*24*30);
                if(isset($_COOKIE["name"])){
                    $username=$_COOKIE["name"];
                    setcookie('username', $username, $expire);
                }
                if(isset($_COOKIE['email'])){
                setcookie('email',$new_email,$expire);
                }
                header("refresh:4;url=settings.php");
            }else{
                echo "Wrong code";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Function to start countdown timer
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    <?php if (isset($_SESSION['Signup_Verificationcode'])):?>
                      window.location.href = "signup.php";
                      <?php elseif (isset($_SESSION['Password_Verification'])): ?>
                      window.location.href = "forget.php";
                    <?php elseif (isset($_SESSION['verfEmail'])): 
                     if(!isset($_SESSION['checkLogin'])&&isset($_COOKIE['username'])){
                          $old_email=$_COOKIE['email'];
                         }
                    else{
                      $old_email=$_SESSION['old_email'];
                       }
                $_SESSION['email'] = $old_email;
        ?>
            window.location.href = "settings.php";
        <?php endif; ?>
    }
            }, 1000);
        }

        // Execute timer when the page is loaded
        window.onload = function () {
            var startTime = <?php echo $_SESSION['start_time']; ?>;
            var currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
            var elapsedTime = currentTime - startTime; // Time elapsed since page loaded
            var timeRemaining = 60 - elapsedTime; // Remaining time in seconds
            var display = document.querySelector('#time');
            startTimer(timeRemaining, display);
        };
    </script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Verify Your Code</h2>
        <p class="text-center">Enter the verification code you received in your email:</p>
        <form action="verify.php" method="post" class="text-center">
            <div class="form-group">
                <input type="number" name="code" class="form-control" placeholder="Verification Code" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="verify">Verify</button>
        </form>
        <div class="text-center mt-3 timer">
            Time left: <span id="time">01:00</span>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>