<?php
  include_once("DatabaseInterface.php");
  include_once("Session.php");
  include_once("DatabaseClassDisplayHelper.php");

  StartSession();

  $user = null;
  // Figure out which user to view
  if (isset($_GET["user"])) {
    // Specified user.
    $user = GetUser($_GET["user"]);
    // TODO: prevent a user being viewed if $user->IsPublic is false and not logged in as said user.
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
  <link rel="stylesheet" href="DatabaseClassDisplayStyle.css" />
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
    <?php
    $artefacts = $user->GetArtefacts();
    foreach($artefacts as $artefact){
      GenerateArtefactBox($artefact);
    }
    ?>
    
    <h2 id="work" >Work Experience</h2>

    <?php
      // Generate the timeline of work experience.
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