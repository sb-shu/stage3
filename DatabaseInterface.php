<!--
    Edited by: James Burdett
    Last edited: 23/03/2022
-->

<?php

// Constants

define("SQLPATH", "./database.sqlite");
// Classes

// Class that holds data for a file that can be shown on a user's profile.
class PortfolioArtefact{
    public $ID; // Int
    // Link to a thumbnail image to display.
    public $ThumbnailLink; // String
    // Link to the file, such as a pdf, png, or any other extension.
    public $FileLink; // String
    public $Tags; // String[]

    // Saves changes to this object to database.
    // Returns: void
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

// Class that holds data about a person's past work experience.
class PortfolioWorkExperience{
    public $ID; // Int
    public $StartDate; // Date
    public $EndDate; // Date
    public $JobTitle; // String
    public $Description; // String

    // Saves changes to this object to database.
    // Returns: void
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

// Class that holds all publicly accessable data regarding a user, including name, portfolio items, and more.
class UserAccount{
    public $Username; // String
    public $FirstName; // String
    public $LastName; // String
    public $ProfilePictureLink; // String
    public $AboutMe; // String
    public $Contacts; // String[]

    // Returns: String[]
    function GetEducation(){
        // TODO: Fetch from DB.
    }

    // Returns: String[]
    function GetJobTitles(){
        // TODO: Fetch from DB.
    }

    // Returns: PortfolioWorkExperience[]
    function GetWorkExperience(){
        // TODO: Fetch from DB.
    }

    // Returns: PortfolioArtefact[]
    function GetArtefacts(){
        // TODO: Fetch from DB.
    }

    // Saves changes to this object to database.
    // Returns: void
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

// Initializes tables
// Returns: void
function InitializeDatabase(){
    $db = new SQLite3(SQLPATH);
    // Create UserAccounts table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS UserAccounts (
        Username TEXT PRIMARY KEY,
        PasswordHash TEXT,
        FirstName TEXT,
        LastName TEXT,
        ProfilePictureLink TEXT,
        AboutMeText TEXT,
        Contacts Text
        )'
        );
    // Create EducationInstitutions Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS EducationInstitutions (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Name TEXT
        )'
        );
    // Create JobTitles Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS JobTitles (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Name TEXT
        )'
        );
    // Create PortfolioArtefacts Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS PortfolioArtefacts (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Username TEXT,
        ThumbnailLink TEXT,
        FileLink TEXT,
        Tags TEXT
        )'
        );
    // Create PortfolioWorkExperiences Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS PortfolioWorkExperiences (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Username TEXT,
        StartDate TEXT,
        EndDate TEXT,
        JobTitleID INTEGER,
        Description TEXT
        )'
        );
    // Create UserJobLink Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS UserJobLink (
        Username TEXT,
        JobTitleID INTEGER
        )'
        );
    // Create UserEducationLink Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS UserEducationLink (
        Username TEXT,
        EducationID INTEGER
        )'
        );
}

// === Artefact functions ===

// Gets an artefact from the database with a given id.
// Returns: PortfolioArtefact
function GetArtefact($artefactID){
    // TODO: Get from database.
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

// Deletes a work experience entry from the database with a given id.
// Returns: void
function DeleteWorkExperience($workExperienceID){
    // TODO: Delete from database.
}

// === User functions ===

// Creates a UserAccount. Returns true if successful.
// Returns: Bool
function CreateUser($desiredUsername, $password){
    // If username already exists or password is not complex enough, return false
    // TODO: the logic for that.

    // If requirements are met, create and return true.
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("INSERT INTO UserAccounts (Username, PasswordHash) VALUES (:username, :passwordHash)");
    $statement->bindParam(":username", $desiredUsername);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement->bindParam(":passwordHash", $hash);
    $statement->execute();
    return True;
}

// Gets a user from the database with a given username.
// Returns: UserAccount
function GetUser($username){
    $statement = $db->prepare("SELECT FirstName, LastName, Contacts, ProfilePictureLink, AboutMeText FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(":username", $desiredUsername);
    $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);

    // TODO: If not found return null

    $userObject = new UserAccount();
    $userObject->Username = $username;
    $userObject->FirstName = $result["FirstName"];
    $userObject->LastName = $result["LastName"];
    $userObject->ProfilePictureLink = $result["ProfilePictureLink"];
    $userObject->AboutMe = $result["AboutMeText"];
    $userObject->Contacts = explode("\0",$result["Contacts"]);
}

// Deletes a user from the database with a given username.
// Returns: void
function DeleteUser($username){
    // TODO : Delete from database.
}

// Compares a user's password. Returns true if the passwords match.
// Returns: bool
function UserComparePassword($username, $password){
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("SELECT PasswordHash FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(':username', $username);
    $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);
    
    return password_verify($password, $result["PasswordHash"]);
}

// Temporary call to initialize the database.
InitializeDatabase();
?>
