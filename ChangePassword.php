<?php
session_start();

//This page change the user password in forget password case.

include 'connect.php';
if (!isset($_SESSION['EmailForget'])) {
    die("error");
}

if (isset($_POST['change'])) {
    $password = addslashes($_POST['newpassword']);
    $hashedpass_full = hash('sha256', $password); // Full hashed password
    $pass = substr($hashedpass_full, 0, 30);
    $email = $_SESSION['EmailForget'];
    $sql = "update signup set password='$pass' where email='$email'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Error";
    } else {
        echo "Password Changed Successfully";
        session_destroy();
        setcookie('username', '', time() - 3600);
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function check(input, type) {
            var x = new XMLHttpRequest();
            x.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText === "invalid") {
                        if (type === "password") {
                            document.getElementById("passErrorMessage").innerHTML = "Password must be at least 5 characters length and contain at least a letter and a number";
                        } else if (type === "confirmPassword") {
                            document.getElementById("confirmPassErrorMessage").innerHTML = "Passwords do not match";
                        }
                    } else {
                        if (type === "password") {
                            document.getElementById("passErrorMessage").innerHTML = "";
                        } else if (type === "confirmPassword") {
                            document.getElementById("confirmPassErrorMessage").innerHTML = "";
                        }
                    }
                }
            };

            var formData = new FormData();
            formData.append('input', input);
            formData.append('type', type);
            
            if (type === "confirmPassword") {
                var password = document.getElementsByName("newpassword")[0].value;
                formData.append('password', password);
            }

            x.open("POST", "check.php", true);
            x.send(formData);
        }

        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPass = document.getElementById("confirmPass").value;

            var isValid = (password.length >= 5 && /\d/.test(password) && /[a-zA-Z]/.test(password)) && (password === confirmPass);

            document.getElementById("signupButton").disabled = !isValid;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="ChangePassword.php" method="post">
                            <div class="form-group">
                                <label for="password">New password:</label>
                                <input type="password" class="form-control" name="newpassword" id="password" oninput="check(this.value, 'password'); validateForm();" required>
                                <small class="form-text text-danger" id="passErrorMessage"></small>
                            </div>
                            <div class="form-group">
                                <label for="confirmPass">Confirm new password:</label>
                                <input type="password" class="form-control" name="confirmPass" id="confirmPass" oninput="check(this.value, 'confirmPassword'); validateForm();" required>
                                <small class="form-text text-danger" id="confirmPassErrorMessage"></small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" id="signupButton" name="change" disabled>Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>