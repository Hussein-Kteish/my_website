<?php 
session_start();

//This page show my skills in different domains in programming.

if(!isset($_SESSION['checkLogin']) && !isset($_COOKIE['username'])){
    header("refresh:4;url=login.php");
    die("Please login to your account first to open this page");
}
?>
<!DOCTYPE html> 
<html> 
<head>
 <link rel="stylesheet" type="text/css" href="skills.css">
 
 <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 <title>My Skills</title> 
</head> 
<body>
  <div class="menu-toggle" id="menuIcon">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
  <div class="menu-content" id="menuContent">
    <a href="home.php">Home</a>
    <a href="contact.php">Contact</a>
    <?php 
    if(isset($_SESSION['checkLogin']) || isset($_COOKIE['username'])){
    echo "<a href=settings.php>Settings</a>";
    }
    ?>
  </div>
<div class="container"> 
  
 <!--   *  Main Heading Starts  *   --> 
 
 <div class="main-title"> 
  <h1>My Skills</h1> 
  <p>Always Remember! Their is new thing to learn in programming every day...</p> 
 </div> 
 
 <!--   *  Main Heading Ends  *   --> 
 
 <div class="row"> 
   
  <!--   *  Left Section Starts here  *   --> 
 
  <section class="col"> 
    
   <div class="sub-title"> 
    <h2>Web Development</h2> 
   </div> 
 
   <div class="skills-container"> 
     
    <div class="skill"> 
     <div class="subject">HTML</div> 
     <div class="progress-bar" value="99%"> 
      <div class="progress-line" style="max-width: 99%"></div> 
     </div> 
    </div> 
 
    <div class="skill"> 
     <div class="subject">CSS</div> 
     <div class="progress-bar" value="83%"> 
      <div class="progress-line" style="max-width: 83%"></div> 
     </div> 
    </div> 
 
                <div class="skill"> 
     <div class="subject">JavaScript</div> 
     <div class="progress-bar" value="90%"> 
      <div class="progress-line" style="max-width: 90%"></div> 
     </div> 
    </div> 
 
    <div class="skill"> 
     <div class="subject">Php</div> 
     <div class="progress-bar" value="98%"> 
      <div class="progress-line" style="max-width: 98%"></div> 
     </div> 
    </div> 

    <div class="skill"> 
     <div class="subject">React</div> 
     <div class="progress-bar" value="70%"> 
      <div class="progress-line" style="max-width: 70%"></div> 
     </div> 
    </div> 

    <div class="skill"> 
     <div class="subject">ASP.NET</div> 
     <div class="progress-bar" value="90%"> 
      <div class="progress-line" style="max-width: 90%"></div> 
     </div> 
    </div> 
 
   </div> 
  </section> 
 
  <!--   **  Left Section Ends Here  *  --> 
 
  <!--   **  Right Section Starts Here  **  --> 
 
  <section class="col"> 
    
   <div class="sub-title"> 
    <h2>Programming Languages</h2> 
   </div> 
 
   <div class="skills-container"> 
     
    <div class="skill"> 
     <div class="subject">C</div> 
     <div class="progress-bar" value="98%"> 
      <div class="progress-line" style="max-width: 98%"></div> 
     </div> 
    </div> 
 
    <div class="skill"> 
     <div class="subject">Dart</div> 
     <div class="progress-bar" value="70%"> 
      <div class="progress-line" style="max-width: 70%"></div> 
     </div> 
    </div> 

    <div class="skill"> 
     <div class="subject">C#</div> 
     <div class="progress-bar" value="80%"> 
      <div class="progress-line" style="max-width: 80%"></div> 
     </div> 
    </div> 

    <div class="skill"> 
     <div class="subject">Shell</div> 
     <div class="progress-bar" value="90%"> 
      <div class="progress-line" style="max-width: 90%"></div> 
     </div> 
    </div> 

    <div class="skill"> 
     <div class="subject">Java</div> 
     <div class="progress-bar" value="90%"> 
      <div class="progress-line" style="max-width: 90%"></div> 
     </div> 
    </div> 
   </div> 
  </section> 
 
  <!--   **  Right Section Ends Here  **   --> 
 </div> 

 <div class="row"> 
   
    <!--   *  Left Section Starts here  *   --> 
   
    <section class="col"> 
      
     <div class="sub-title"> 
      <h2>Production Tools</h2> 
     </div> 
   
     <div class="skills-container"> 

     <div class="skill"> 
       <div class="subject">VS Code</div> 
       <div class="progress-bar" value="99%"> 
        <div class="progress-line" style="max-width: 99%"></div> 
       </div> 
      </div>

      <div class="skill"> 
       <div class="subject">Android Studio</div> 
       <div class="progress-bar" value="95%"> 
        <div class="progress-line" style="max-width: 95%"></div> 
       </div> 
      </div>
       
      <div class="skill"> 
       <div class="subject">Jupyter Notebook</div> 
       <div class="progress-bar" value="90%"> 
        <div class="progress-line" style="max-width: 90%"></div> 
       </div> 
      </div> 
 

      <div class="skill"> 
       <div class="subject">SQL Server</div> 
       <div class="progress-bar" value="99%"> 
        <div class="progress-line" style="max-width: 99%"></div> 
       </div> 
      </div> 

      <div class="skill"> 
       <div class="subject">Visual Studio</div> 
       <div class="progress-bar" value="90%"> 
        <div class="progress-line" style="max-width: 90%"></div> 
       </div> 
      </div> 

      <div class="skill"> 
       <div class="subject">NetBeans</div> 
       <div class="progress-bar" value="95%"> 
        <div class="progress-line" style="max-width: 95%"></div> 
       </div> 
      </div> 
   
     </div> 
    </section> 
   
    <!--   **  Left Section Ends Here  *  --> 
   
    <!--   **  Right Section Starts Here  **  --> 
   
    <section class="col"> 
      
     <div class="sub-title"> 
      <h2>Database Managment</h2> 
     </div> 
   
     <div class="skills-container"> 
       
      <div class="skill"> 
       <div class="subject">SQL</div> 
       <div class="progress-bar" value="99%"> 
        <div class="progress-line" style="max-width: 99%"></div> 
       </div> 
      </div> 

     </div> 
    </section> 
   
    <!--   **  Right Section Ends Here  **   --> 
   </div> 

   <div class="row"> 
   
    <!--   *  Left Section Starts here  *   --> 
   
    <section class="col"> 
      
     <div class="sub-title"> 
      <h2>Operating System Knowledge</h2> 
     </div> 
   
     <div class="skills-container"> 
       
      <div class="skill"> 
       <div class="subject">Windows</div> 
       <div class="progress-bar" value="95%"> 
        <div class="progress-line" style="max-width: 95%"></div> 
       </div> 
      </div> 

      <div class="skill"> 
        <div class="subject">Linux</div> 
        <div class="progress-bar" value="97%"> 
         <div class="progress-line" style="max-width: 97%"></div> 
        </div> 
       </div> 
   
     </div> 
    </section> 
   
    <!--   **  Left Section Ends Here  *  --> 
   
    <!--   **  Right Section Starts Here  **  --> 
   
    <section class="col"> 
      
     <div class="sub-title"> 
      <h2>Software Enginneering</h2> 
     </div> 
   
     <div class="skills-container"> 
       
      <div class="skill"> 
       <div class="subject">UML Diagrams</div> 
       <div class="progress-bar" value="99%"> 
        <div class="progress-line" style="max-width: 99%"></div> 
       </div> 
      </div> 

      <div class="skill"> 
        <div class="subject">SRS Creation</div> 
        <div class="progress-bar" value="99%"> 
         <div class="progress-line" style="max-width: 99%"></div> 
        </div> 
       </div> 

       <div class="skill"> 
        <div class="subject">Conducting Interviews</div> 
        <div class="progress-bar" value="95%"> 
         <div class="progress-line" style="max-width: 95%"></div> 
        </div> 
       </div> 

     </div> 
    </section> 
   
    <!--   **  Right Section Ends Here  **   --> 
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