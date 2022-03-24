<?php

/* #region Constants */

define("SQLPATH", "./database.sqlite");
define("ARRAYSEPERATOR","\n");

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

    private function __constructor(){
        
    }

    /* #region Create, Get, Save, Delete */
    static function CreateArtefact() : ?PortfolioArtefact{
        // TODO: Create the artefact in the database.
    }

    static function GetArtefact($artefactID) : ?PortfolioArtefact {  
        $db = new SQLite3(SQLPATH);

        $statement = $db->prepare("SELECT Title, Description, ThumbnailLink, FileLink, Tags FROM PortfolioArtefacts WHERE ID = :id");
        $statement->bindParam(":id", $artefactID);
        $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);
    
        // If query returns empty, return.
        if (!$result) {
            return null;
        }
    
        $artefact = new PortfolioArtefact();
        $artefact->ID = $artefactID;
        $artefact->Title = $result["Title"];
        $artefact->Description = $result["Description"];
        $artefact->FileLink = $result["FileLink"];
        $artefact->ThumbnailLink = $result["ThumbnailLink"];
        $artefact->Tags = explode(ARRAYSEPERATOR,$result["Tags"]);
        return $artefact;
    }

    // Saves changes to this object to database.
    function SaveChanges(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("UPDATE PortfolioArtefacts SET
        Title = :title,
        Description = :description,
        ThumbnailLink = :thumbnailLink,
        FileLink = :fileLink,
        Tags = :tags
        WHERE ID = :id");
        $statement->bindParam(":id", $this->ID);

        $statement->bindParam(":title", $this->Title);
        $statement->bindParam(":description", $this->Description);
        $statement->bindParam(":thumbnailLink", $this->ThumbnailLink);
        $statement->bindParam(":fileLink", $this->FileLink);
        $statement->bindParam(":tags", implode(ARRAYSEPERATOR,$this->Tags));

        $statement->execute();
    }

    /* #endregion */
}

// Class that holds data about a person's past work experience.
class PortfolioWorkExperience{
    private $ID; // Int
    public $StartDate; // Date
    public $EndDate; // Date
    public $JobTitle; // String
    public $Description; // String

    private function __constructor(){
        
    }

    /* #region Create, Get, Save, Delete */

    // Saves changes to this object to database.
    function SaveChanges(){
        // TODO: Write fields to database.
    }

    /* #endregion */
}

// Class that holds all publicly accessable data regarding a user, including name, portfolio items, and more.
class UserAccount{
    private $Username; // String
    public $FirstName; // String
    public $LastName; // String
    public $ProfilePictureLink; // String
    public $AboutMe; // String
    public $Contacts; // String[]

    private function __constructor(){

    }

    /* #region Create, Get, Save, Delete */
    static function CreateUserAccount($desiredUsername, $password) : ?UserAccount{
        $db = new SQLite3(SQLPATH);

        // Check if username already exists...
        if(DoesUserExist($desiredUsername))
            return null;

        // If requirements are met, create and return true.

        $statement = $db->prepare("INSERT INTO UserAccounts (Username, PasswordHash) VALUES (:username, :passwordHash)");
        $statement->bindParam(":username", $desiredUsername);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement->bindParam(":passwordHash", $hash);
        $statement->execute();
        return UserAccount::GetUserAccount($desiredUsername);
    }

    static function GetUserAccount($username) : ?UserAccount{
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
        $userObject->Contacts = explode(ARRAYSEPERATOR,$result["Contacts"]);

        return $userObject;
    }

    // Saves changes to this object to database.
    function SaveChanges(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("UPDATE UserAccounts SET 
        FirstName = :firstName,
        LastName = :lastName,
        ProfilePictureLink = :profilePictureLink,
        AboutMeText = :aboutMeText,
        Contacts = :contacts
        WHERE Username = :username");
        $statement->bindParam(":username", $this->Username);

        $statement->bindParam(":firstName", $this->FirstName);
        $statement->bindParam(":lastName", $this->LastName);
        $statement->bindParam(":profilePictureLink", $this->ProfilePictureLink);
        $statement->bindParam(":aboutMeText", $this->AboutMeText);
        $statement->bindParam(":contacts", implode(ARRAYSEPERATOR,$this->Contacts));

        $statement->execute();
    }

    /* #endregion */

    function GetEducation(){
        // TODO: Fetch from DB.
    }

    function GetJobTitles(){
        // TODO: Fetch from DB.
    }

    function GetWorkExperience(){
        // TODO: Fetch from DB.
    }

    /* #region Artefacts */

    // Creates a new artefact attached to this account.
    function AddNewArtefact() : PortfolioArtefact{
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("INSERT INTO PortfolioArtefacts (Username) VALUES (:username)");
        $statement->bindParam(":username", $this->Username);
        $statement->execute();

        return PortfolioArtefact::GetArtefact($db->lastInsertRowid());
    }

    // Returns all artefacts attached to this account.
    function GetArtefacts(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("SELECT ID FROM PortfolioArtefacts WHERE Username = :username");
        $statement->bindParam(":username", $this->Username);

        $artefacts = [];
        $count = 0;

        $result = $statement->execute();
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $artefacts[$count++] = PortfolioArtefact::GetArtefact($row["ID"]);
        }

        return $artefacts;
    }

    /* #endregion */
}

/* #endregion */

/* #region User Functions */

// Creates a UserAccount. Returns a UserAccount object if successful.
function CreateUser($desiredUsername, $password) : ?UserAccount{
    return UserAccount::CreateUserAccount($desiredUsername, $password);
}

// Gets a user from the database with a given username.
function GetUser($username) : ?UserAccount{
    return UserAccount::GetUserAccount($username);
}

// Runs a query to check if a username is in use.
function DoesUserExist($username) : Bool{
    $db = new SQLite3(SQLPATH);
    $statement = $db->prepare("SELECT Username FROM UserAccounts WHERE Username = :username");
    $statement->bindParam(":username", $username);
    if($statement->execute()->fetchArray())
        return true;
    return false;
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
        Title TEXT,
        Description TEXT,
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
$newAccount = CreateUser("testuser", "pass");
if($newAccount){
    // Set new account details...
    $newAccount->FirstName = "Test";
    $newAccount->LastName = "Testington";
    $newAccount->ProfilePictureLink = "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.thetimes.co.uk%2Farticle%2Frick-astley-the-internet-s-oldest-joke-is-having-the-last-laugh-kwksbq757&psig=AOvVaw2ENgG_QGvmQTUzZ9zN1FJu&ust=1648216198875000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCOCqt_nx3vYCFQAAAAAdAAAAABAD";
    $newAccount->AboutMeText = "I am a music person.";
    $newAccount->Contacts = ["Youtube: some link", "Github: Some link"];

    // Save the changes made.
    $newAccount->SaveChanges();

    // Add artefacts...
    for($i = 0; $i < 5; $i++){
        $artefact = $newAccount->AddNewArtefact();
        $artefact->Title = "Artefact ".$i+1;
        $artefact->Description = "This is artefact number ".($i+1).".";
        $artefact->ThumbnailLink = "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.thetimes.co.uk%2Farticle%2Frick-astley-the-internet-s-oldest-joke-is-having-the-last-laugh-kwksbq757&psig=AOvVaw2ENgG_QGvmQTUzZ9zN1FJu&ust=1648216198875000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCOCqt_nx3vYCFQAAAAAdAAAAABAD";
        $artefact->FileLink = "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.thetimes.co.uk%2Farticle%2Frick-astley-the-internet-s-oldest-joke-is-having-the-last-laugh-kwksbq757&psig=AOvVaw2ENgG_QGvmQTUzZ9zN1FJu&ust=1648216198875000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCOCqt_nx3vYCFQAAAAAdAAAAABAD";
        $artefact->Tags = ["Year ".$i+1, "University"];
        // Save the changes made.
        $artefact->SaveChanges();
    }

    // How to get all artefacts
    $artefacts = $newAccount->GetArtefacts();
}
?>
