<?php
  include_once("DatabaseInterface.php");
  include_once("Session.php");
  include_once("DatabaseClassDisplayHelper.php");

  // Posting profile changes
  if(isset($_POST["submit"])){
    var_dump($_POST);
    die;
  }

  StartSession();

  $user = null;
  $editingEnabled = false;
  // Figure out which user to view
  if (isset($_GET["user"])) {
    // Specified user.
    $user = GetUser($_GET["user"]);
    // TODO: prevent a user being viewed if $user->IsPublic is false and not logged in as said user.
  } else if (IsLoggedIn()) {
    // No user was specified, so use the current one.
    $user = GetSessionUser();
    $editingEnabled = true;
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

    <h1><?=$user->FirstName;?> <?=$user->LastName;?></h2>
    <h2>Education:</h2><p> <?php echo implode("<br>",$user->GetEducation()); ?></p>
    <h2>Contact:</h2><p> <?php echo implode("<br>",$user->Contacts); ?></p>
    <h2 id="aboutme">About Me</h3><p><?php echo $user->AboutMe; ?></p>
    
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
      GenerateArtefactBox($artefact,$editingEnabled);
    }
    ?>

    <h2 id="work" >Work Experience</h2>

    <?php
      // Generate the timeline of work experience.
      GenerateWorkExperienceTimeline($user->GetWorkExperience(),$editingEnabled);
    ?>

  </div>
</div>
</body>
</html>