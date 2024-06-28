<?php
session_start();
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
      session_destroy();
    }
  }

if(isset($_POST['send'])){
    $emailForget=addslashes($_POST['email']);
    $_SESSION['EmailForget']=$emailForget;
    $sql="select * from signup where email='$emailForget'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)!=1){
        echo "Email doesn't exist";
        header("refresh:5;url=forget.php");
    }
    else{
         $code=str_pad(rand(111111, 999999), 6, '0', STR_PAD_LEFT);
         session_destroy();//to destroy $_SESSION['Verificationcode'] and $_SESSION['verfEmail'] if any of them exist
         session_start();
         $_SESSION['Password_Verification']=$code;
         $_SESSION['EmailForget']=$emailForget;//we need it in ChangePassword.php
         $subject = "Forget Password";
         $message = "Your verification code is: <span id='verfCode' style='cursor: pointer; color: blue;'>$code</span>";
        sendEmail($emailForget,$subject,$message);
       }
}
?>
<!doctype html>
<html lang="en">

<head>
<link rel="stylesheet" href="forget.css">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <title>Forgot Password</title>
</head>

<body>
    <div class="card">
        <p class="lock-icon"><img src="images/lock.png" width="20%"></img></p>
        <h2>Forgot Password?</h2>
        <p>You can reset your Password here</p>
        <form action="forget.php "method="post">
        <input type="email" class="passInput" placeholder="write your email here" name="email">
        <input type="submit" class="SendButton" name="send" value="Send verificationt code">
        </form>
    </div>
    <a href="login.php">Back to login?</a>
</body>

</html>