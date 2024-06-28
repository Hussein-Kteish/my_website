<?php
session_start();

// The home page its the first page open after login shows the number of vistors and registered acoounts and also shows some programming quotes.

include 'connect.php';

if(!isset($_COOKIE['nbOfVisitors'])){//number of visitors
    $sql="update visitors set visitorNb=visitorNb+1";
    $result=mysqli_query($conn,$sql);
    $expire = time() + (60 * 60 * 24 * 365 * 100);
    setcookie("nbOfVisitors",1,$expire);
}
    $Sqlnb="select visitorNb from visitors";
    $nb_result=mysqli_query($conn,$Sqlnb);
    $nb_row = mysqli_fetch_assoc($nb_result);
    $visitor_count = $nb_row['visitorNb'];
    $nbOfColumns = "SELECT COUNT(*) AS count FROM signup";
    $nbOfColumnsresult = mysqli_query($conn, $nbOfColumns);
    $nbOfColumnsrow = mysqli_fetch_assoc($nbOfColumnsresult);
    $nbOfAccounts = $nbOfColumnsrow['count'];
    mysqli_free_result($nbOfColumnsresult);
    
?>

<html>
<head>
    <title>Hussein Kteish</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<video autoplay loop muted class="background-video">
  <source src="bg.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
<div class="menu-toggle" id="menuIcon">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
  <div class="menu-content" id="menuContent">
    <a href="skills.php">Skills</a>
    <a href="contact.php">Contact</a>
    <?php 
    if(isset($_SESSION['checkLogin']) || isset($_COOKIE['username'])){//show the settings and menu options if the user is logged in
    echo "<a href=settings.php>Settings</a>";
    echo "<a href=logout.php>Logout</a>";
    }
    ?>
  </div>
<header>
    <img src="hk.jpg" class="homeimage">
    <?php 
    if(isset($_SESSION['checkLogin']) || isset($_COOKIE['username'])) {// get the username from the database
        if(isset($_SESSION['checkLogin'])){
        $Session_user=$_SESSION['checkLogin'];
        $getname="select username from signup where username='$Session_user'";
        }
        if(!isset($_SESSION['checkLogin'])&&isset($_COOKIE['username'])){//we can just put cookie condition since getname will will change its value no error will occur
        $Cookie_user=$_COOKIE['username'];
        $getname="select username from signup where username='$Cookie_user'";
        }
        $result=mysqli_query($conn,$getname);
        $row=mysqli_fetch_array($result);
    echo"<h1 style=color:white;margin-right:60%>Hello ".$row['username']."</h1>";//Hello username
    }
    ?>
</header>
<div id="summary-container">
    <div id="summary">This website was built using HTML, CSS, Bootstrap, JavaScript, and PHP</div>
    <div id="programming-text">Programming is learned by writing programs.</div>
</div>
<div id="nbOfVisitors">
    <?php echo "Number of visitors for the website: ".$visitor_count; ?><br><br>
    <?php echo "Number of registered accounts: ".$nbOfAccounts; ?>
</div><br><br>
<?php
 if(!isset($_SESSION['checkLogin']) && !isset($_COOKIE['username'])){// Suggest message for the user to signup 
    echo "<a style=margin-left:20%;color:green href=signup.php>SignUp in my website?</a>";
}
?>

<script>
    const programmingTexts = [//array contain some quotes
        "Programming is learned by writing programs.",
        "while(noSuccess) { tryAgain(); if(Dead) break; }",
        "Coding is like solving puzzles with a twist.",
        "Programming is the art of algorithm design and the craft of debugging errant code.",
        "while(alive) { doNotGiveUp(); }",
        "In programming, every bug is just an opportunity to learn.",
        "The best code is code that's never written.",
    ];

    let currentIndex = 0;
    const programmingTextElement = document.getElementById("programming-text");

    function changeText() {
        currentIndex = (currentIndex + 1) % programmingTexts.length;
        programmingTextElement.textContent = programmingTexts[currentIndex];
    }
    setInterval(changeText, 10000); // Change text every 5 seconds


    document.getElementById("menuIcon").addEventListener("click", function() {
      this.classList.toggle("change");
      document.getElementById("menuContent").classList.toggle("show");
    });
</script>

</body>
</html>