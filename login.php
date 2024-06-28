<?php
session_start();

//Login page takes the email or username and password of the account and check if they match and rdirect user to home page.


include 'connect.php';

if(isset($_COOKIE['username'])) {// or !isset($_SESSION['user'])
    header("location:home.php");
}


if(isset($_POST['login'])){
    $UserOrEmail=addslashes($_POST['emailUser']);
    $password=addslashes($_POST['password']);
    $hashedpass_full = hash('sha256', $password);  // Full hashed password
    $pass = substr($hashedpass_full, 0, 30);
    $sql="select * from signup where (username='$UserOrEmail' and password='$pass') or (email='$UserOrEmail' and password='$pass')";
    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){//if account exist
        $row=mysqli_fetch_array($result);

        $user=$row['username'];
        $email=$row['email'];
        $_SESSION['checkLogin'] = $user;//save username in a session and use the session later as prove for login and get some data from the database
        $_SESSION['email']=$email;
      
      if(isset($_POST['remember'])){
        $expire=time()+(60*60*24*30);
    }
    else{
     $expire=time()-10;
    }
    /*set cookies according to user choice 
    if remember option is checked cookies will be set to 30 days so user can open the account without login*/
    setcookie('username', $user, $expire);
    setcookie('email',$email,$expire);
    header("location:home.php");
    }
    else{
        echo "<script>alert('error in username or password')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="login.css">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <title>Login</title>
</head>
<body>
    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="form-box border rounded p-4 shadow">
            <div class="form-value">
                <form action="login.php" method="post">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="text" name="emailUser" style="color:white;" required class="form-control">
                        <label for="">Username or Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" style="color:white;" required class="form-control">
                        <label for="">Password</label>
                    </div>
                    <div class="forget d-flex justify-content-between align-items-center">
                        <label><input type="checkbox" name="remember"> Remember Me </label>
                        <a href="forget.php" class="text-warning">Forget Password?</a>
                    </div>
                    <input type="submit" style="color:black;" class="LoginButton btn btn-primary w-100" name="login" value="Log in">
                    <div class="register mt-3 text-center">
                        <p>Don't have an account? <a href="signup.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
