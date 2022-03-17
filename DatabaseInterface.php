<!--
    Edited by: James Burdett
    Last edited: 17/03/2022
-->

<?php

// Class that holds data for a file that can be shown on a user's profile.
class PortfolioArtefact{
    public $ID; // Int
    // Link to a thumbnail image to display.
    public $ThumbnailLink; // String
    // Link to the file, such as a pdf, png, or any other extension.
    public $FileLink; // String
}

// Class that holds data about a person's past work experience.
class PortfolioWorkExperience{
    public $ID; // Int
    public $StartDate; // Date
    public $EndDate; // Date
    public $JobTitle; // String
    public $Description; // String
}

// Class that holds all publicly accessable data regarding a user, including name, portfolio items, and more.
class UserAccount{
    public $Username; // String
    public $FirstName; // String
    public $LastName; // String
    public $ProfilePictureLink; // String
    public $AboutMe; // String

    public $EducationArr; // String[] (Could change to a different object for additional functionality in the future, such as adding a link to the institutions webpage)
    public $JobTitleArr; // String[] (Could change to a different object for additional functionality in the future, such as adding a link to the institutions webpage)
    public $PortfolioArtefacts; // PortfolioArtefact[]
    public $PortfolioWorkExperiences; // PortfolioWorkExperience[]
}

// === Artefact functions ===

// Gets an artefact from the database with a given id.
// Returns: PortfolioArtefact
function GetArtefact($artefactID){
    // TODO: Get from database.
}

// Sets an artefact entry in the database. The key is provided within the object.
// Returns: void
function SetArtefact($artefact){
    // TODO: Set in database.
}

// Deletes an artefact from the database with a given id.
// Returns: void
function DeleteArtefact($artefactID){
    // TODO: Delete from database.
}

// === Work Experience functions ===

// Gets a work experience entry from the database with a given id.
// Return: PortfolioWorkExperience
function GetWorkExperience($workExperienceID){
    // TODO: Get from database.
}

// Sets a work experience entry in the database. The key is provided within the object.
// Returns: void
function SetWorkExperience($workExperience){
    // TODO: Set in database.
}

// Deletes a work experience entry from the database with a given id.
// Returns: void
function DeleteWorkExperience($workExperienceID){
    // TODO: Delete from database.
}

// === User functions ===

// Gets a user from the database with a given username.
// Returns: UserAccount
function GetUser($username){
    // TODO: Get from database.
}

// Sets a user in the database. The key is provided within the object.
// Returns: void
function SetUser($userAccount){
    // TODO: Set in database.
}

// Deletes a user from the database with a given id.
// Returns: void
function DeleteUser($userAccountID){
    // TODO : Delete from database.
}

?>