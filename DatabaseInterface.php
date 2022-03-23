<!--
    Edited by: James Burdett
    Last edited: 23/03/2022
-->

<?php

/* #region Constants */

define("SQLPATH", "./database.sqlite");

/* #endregion */

/* #region Classes */

// Class that holds data for a file that can be shown on a user's profile.
class PortfolioArtefact{
    private $ID; // Int
    public $Title;
    public $Description;
    // Link to a thumbnail image to display.
    public $ThumbnailLink; // String
    // Link to the file, such as a pdf, png, or any other extension.
    public $FileLink; // String
    public $Tags; // String[]

    // Saves changes to this object to database.
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

// Class that holds data about a person's past work experience.
class PortfolioWorkExperience{
    private $ID; // Int
    public $StartDate; // Date
    public $EndDate; // Date
    public $JobTitle; // String
    public $Description; // String

    // Saves changes to this object to database.
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

// Class that holds all publicly accessable data regarding a user, including name, portfolio items, and more.
class UserAccount{
    private $Username; // String
    public $FirstName; // String
    public $LastName; // String
    public $ProfilePictureLink; // String
    public $AboutMe; // String
    public $Contacts; // String[]

    function GetEducation(){
        // TODO: Fetch from DB.
    }

    function GetJobTitles(){
        // TODO: Fetch from DB.
    }

    function GetWorkExperience(){
        // TODO: Fetch from DB.
    }

    function AddNewArtefact() : PortfolioArtefact{
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("INSERT INTO PortfolioArtefacts (Username) VALUES (:username)");
        $statement->bindParam(":username", $this->Username);
        var_dump($statement->getSQL());
        $statement->execute();
        var_dump($db->lastInsertRowid());

        return GetArtefact($db->lastInsertRowid());
    }

    function GetArtefacts(){
        // TODO: Fetch from DB.
    }

    // Saves changes to this object to database.
    function SaveChanges(){
        // TODO: Write fields to database.
    }
}

/* #endregion */

/* #region User Functions */

// Creates a UserAccount. Returns true if successful.
function CreateUser($desiredUsername, $password) : bool{
    $db = new SQLite3(SQLPATH);

    // Check if username already exists...
    $statement = $db->prepare("SELECT Username FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(":username", $desiredUsername);
    $result = $statement->execute()->fetchArray();
    if($result)
        return false;

    // If requirements are met, create and return true.

    $statement = $db->prepare("INSERT INTO UserAccounts (Username, PasswordHash) VALUES (:username, :passwordHash)");
    $statement->bindParam(":username", $desiredUsername);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement->bindParam(":passwordHash", $hash);
    $statement->execute();
    return true;
}

// Gets a user from the database with a given username.
function GetUser($username) : UserAccount{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("SELECT FirstName, LastName, Contacts, ProfilePictureLink, AboutMeText FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(":username", $username);
    $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);

    // If query returns empty, return.
    if (!$result) {
        return null;
    }

    $userObject = new UserAccount();
    $userObject->Username = $username;
    $userObject->FirstName = $result["FirstName"];
    $userObject->LastName = $result["LastName"];
    $userObject->ProfilePictureLink = $result["ProfilePictureLink"];
    $userObject->AboutMe = $result["AboutMeText"];
    $userObject->Contacts = explode("\0",$result["Contacts"]);

    return $userObject;
}

// Deletes a user from the database with a given username.
function DeleteUser($username){
    // TODO : Delete from database.
}

// Compares a user's password. Returns true if the passwords match.
function UserComparePassword($username, $password):bool{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("SELECT PasswordHash FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(':username', $username);
    $result = $statement->execute();

    // If query returns empty, return.
    if (!$result) {
        return false;
    }
    
    return password_verify($password, $result["PasswordHash"]);
}

/* #endregion */


// Initializes tables
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

// Call to initialize the database.
InitializeDatabase();

// Create a testing account.
if(CreateUser("testuser", "pass")){
    $user = GetUser("testuser");
    for($i = 0; $i < 5; $i++){
        $artefact = $user->AddNewArtefact();
    }
}
?>
