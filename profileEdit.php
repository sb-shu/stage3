<?php
  include_once("Session.php");
  include_once("DatabaseInterface.php");
  $user = GetSessionUser();
  if(isset($_POST["WorkExperienceSave"])){
    // Check if the id is owned by the user.
    $workExperience = PortfolioWorkExperience::GetWorkExperience($_POST["ID"]);
    $workExperience->JobTitle = $_POST["JobTitle"];
    $workExperience->Description = $_POST["Description"];
    $workExperience->SaveChanges();
  }
  else if(isset($_POST["WorkExperienceDelete"])){
    // Check if the id is owned by the user, then delete.
  }
  else if(isset($_POST["ArtefactSave"])){
    // Check if the id is owned by the user.

    $artefact = PortfolioArtefact::GetArtefact($_POST["ID"]);
    $artefact->Title = $_POST["Title"];
    $artefact->FileLink = $_POST["FileLink"];
    $artefact->ThumbnailLink = $_POST["ThumbnailLink"];

    $artefact->SaveChanges();
  }
  else if(isset($_POST["ArtefactDelete"])){
      // Check if the id is owned by the user, then delete.
  }

  header("Location: profileView.php");
?>