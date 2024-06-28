<?php
session_start();
//This page shows social accounts.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Profiles</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        img.bg{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .container {
            margin-left: 10%;
        }

        .social-icons {
            display: relative;
            margin-top: 2%;
        }

        .icon {
            margin: 0 10px;
        }

        .icon img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .icon img:hover {
            transform: scale(1.1);
        }
        h1{
            color:white;text-align:center;
        }
        h2{
            color:white;
        }
    </style>
</head>
<body>
    <img src="images/socials.jpg" class="bg">
    <div class="menu-toggle" id="menuIcon">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
  <div class="menu-content" id="menuContent">
    <a href="home.php">Home</a>
    <?php 
    if(isset($_SESSION['checkLogin']) || isset($_COOKIE['username'])){
    echo "<a href=settings.php>Settings</a>";
    }
    ?>
  </div>
    <h1>Connect with Me</h1>
    <div class="container">
        <div class="social-icons">
            <a href="https://www.facebook.com/HKhacker.Game.Over" target="_blank" class="icon">
                <img src="logos/fb.avif" alt="Facebook"> <h2>Facebook</h2>
            </a><br><br><br><br>
            <a href="https://github.com/HK-hacker" target="_blank" class="icon">
                <img src="logos/github.png" alt="GitHub"><h2>Github</h2>
            </a><br><br><br><br>
            <a href="https://www.linkedin.com/in/hussein-kteish-852b50244" target="_blank" class="icon">
                <img src="logos/LinkedIn.webp" alt="LinkedIn"><h2>LinkedIn</h2>
            </a>
        </div>
    </div>
    <script>
        document.getElementById("menuIcon").addEventListener("click", function() {
            this.classList.toggle("change");
            document.getElementById("menuContent").classList.toggle("show");
          });
      </script>
</body>
</html>