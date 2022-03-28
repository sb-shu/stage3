<?php
  include_once("DatabaseInterface.php");
  include_once("Session.php");

  StartSession();

  $user = null;
  // Figure out which user to view
  if (isset($_GET["user"])) {
    // Specified user.
    $user = GetUser($_GET["user"]);
  } else if (IsLoggedIn()) {
    // No user was specified, so use the current one.
    $user = GetSessionUser();
  } else {
    // No user to view.
    // TODO: proper error handling
    echo "You are not logged in and have not specified a user whose profile you wish to view. Please <a href='home.php'>log in</a>";
    die;
  }
?>

<html>
<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="TimelineStyle.css" />
</head>
<body>

<div class="split left">
  <div class="top">
    <img src="Donny.webp" alt="Profile Picture">
    <h2>Name: <?=$user->FirstName;?> <?=$user->LastName;?></h2>
    <p>University: Sheffield Hallam</p>
    <p>Date: 2021 - Current Date</p>
    <p>Education: Moira High School</p>
    <p>Date: 2016-2021</p>
    <p>Github Link: https/jameslovescoding.com</p>
    <p>Phone Number: 07593045679</p>
    <p>Email: c1006465@my.shu.ac.uk</p>
  </div>
</div>





<div class="split right">
  <div class="topnav">
    <a class="active" href="#artefacts">Artefacts</a>
    <a href="#work">Work</a>
    <a href="#aboutme">About Me</a>
  </div>

  <div class="undernav">

    <h2 id="artefacts" >Artefacts</h2>
    <a href="#year1">Year 1</a>
    <a href="#year2">Year 2</a>
    <a href="#year3">Year 3</a>
    <a href="#year4">Year 4</a>

    <h3 id="year1">Year 1</h2>
    
    <?php
    $artefacts = $user->GetArtefacts();
    $length = count($artefacts)-1;
    for ($x = 0; $x <= $length; $x++) {
      echo $artefacts[$x]->Title;
      echo "<br>";
      echo $artefacts[$x]->Description;
      echo "<br>";
      echo $artefacts[$x]->ThumbnailLink;
      echo "<br>";
      echo $artefacts[$x]->FileLink;  
      foreach($artefacts[$x]->Tags as $tag){
        echo $tag . "\n";
      echo "<br>";
    }
    }
    ?>

    <h3 id="year2">Year 2</h2>

    <h3 id="year3">Year 3</h2>

    <h3 id="year4">Year 4</h2>
   
    <h2 id="work" >Work Experience</h2>

    <?php
      // Generate the timeline of work experience.
      include_once("WorkExperienceTimelineMaker.php");
      GenerateWorkExperienceTimeline($user->GetWorkExperience());
    ?>

    <h2 id="aboutme">About Me</h2>
      <p>
      Due to graduate in 2021, I have acquired technical knowledge and skills from my course as well as practical and business skills 
      from my industrial year in a software company in Germany. I have used a range of languages, operating systems and development tools 
      as well as experiencing the system development life cycle. Specialising in mobile technology, I am keen to develop as a graduate 
      trainee in software development.
      </p>
  </div>
</div>
</body>
</html>