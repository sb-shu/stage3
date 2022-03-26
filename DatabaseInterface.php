<?php

/* #region Constants */

define("SQLPATH", "./database.sqlite");
define("ARRAYSEPERATOR","\n");

/* #endregion */

/* #region Classes */

// Class that holds data for a file that can be shown on a user's profile.
class PortfolioArtefact{
    private $_ID; // Int
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
    static function CreateArtefact($linkedUsername) : PortfolioArtefact{
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("INSERT INTO PortfolioArtefacts (Username) VALUES (:username)");
        $statement->bindParam(":username", $linkedUsername);
        $statement->execute();

        return PortfolioArtefact::GetArtefact($db->lastInsertRowid());
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
        $artefact->_ID = $artefactID;
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
        $statement->bindParam(":id", $this->_ID);

        $statement->bindParam(":title", $this->Title);
        $statement->bindParam(":description", $this->Description);
        $statement->bindParam(":thumbnailLink", $this->ThumbnailLink);
        $statement->bindParam(":fileLink", $this->FileLink);
        $implodedTags = implode(ARRAYSEPERATOR,$this->Tags);
        $statement->bindParam(":tags", $implodedTags);

        $statement->execute();
    }

    /* #endregion */
}

// Class that holds data about a person's past work experience.
class PortfolioWorkExperience{
    private $_ID; // Int
    public $StartDate; // Date
    public $EndDate; // Date
    private $_WorkInstitutionID;
    public $JobTitle; // String
    public $Description; // String

    private function __constructor(){
        
    }

    /* #region Create, Get, Save, Delete */

    static function CreateWorkExperience($linkedUsername, $workInstitutionID) : PortfolioWorkExperience{
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("INSERT INTO PortfolioWorkExperiences (Username, WorkInstitutionID) VALUES (:username, :workInstitutionID)");
        $statement->bindParam(":username", $linkedUsername);
        $statement->bindParam(":workInstitutionID", $workInstitutionID);
        $statement->execute();

        return PortfolioWorkExperience::GetWorkExperience($db->lastInsertRowid());
    }

    static function GetWorkExperience($workExperienceID) : ?PortfolioWorkExperience{
        $db = new SQLite3(SQLPATH);

        $statement = $db->prepare("SELECT StartDate, EndDate, WorkInstitutionID, JobTitle, Description FROM PortfolioWorkExperiences WHERE ID = :id");
        $statement->bindParam(":id", $workExperienceID);
        $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);
    
        // If query returns empty, return.
        if (!$result) {
            return null;
        }
    
        $workExperience = new PortfolioWorkExperience();
        $workExperience->_ID = $workExperienceID;
        $workExperience->_WorkInstitutionID = $result["WorkInstitutionID"];
        $workExperience->StartDate = $result["StartDate"];
        $workExperience->EndDate = $result["EndDate"];
        $workExperience->JobTitle = $result["JobTitle"];
        $workExperience->Description = $result["Description"];
        return $workExperience;
    }

    // Saves changes to this object to database.
    function SaveChanges(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("UPDATE PortfolioWorkExperiences SET 
        StartDate = :startDate,
        EndDate = :endDate,
        JobTitle = :jobTitle,
        Description = :description
        WHERE ID = :id");
        $statement->bindParam(":id", $this->_ID);

        $statement->bindParam(":startDate", $this->StartDate);
        $statement->bindParam(":endDate", $this->EndDate);
        $statement->bindParam(":jobTitle", $this->JobTitle);
        $statement->bindParam(":description", $this->Description);

        $statement->execute();
    }

    /* #endregion */

    // Returns the name of the work institution.
    function GetWorkInstitutionName(){
        $db = new SQLite3(SQLPATH);

        $statement = $db->prepare("SELECT Name FROM WorkInstitutions WHERE ID = :id");
        $statement->bindParam(":id", $this->_WorkInstitutionID);
        return $statement->execute()->fetchArray(SQLITE3_ASSOC)["Name"];
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
        $implodedContacts = implode(ARRAYSEPERATOR,$this->Contacts);
        $statement->bindParam(":contacts", $implodedContacts);

        $statement->execute();
    }

    /* #endregion */

    /* #region Education */

    // Add an education institution.
    function AddEducation($educationInstitutionName){
        // Check if the institution exists...
        $id = GetEducationInstitution($educationInstitutionName);
        if($id == -1)
            $id = RegisterEducationInstitution($educationInstitutionName);

        // If the institution exists, add it.
        $db = new SQLite3(SQLPATH);

        $statement = $db->prepare("INSERT INTO UserEducationLink (Username, EducationID) VALUES (:username, :educationID)");
        $statement->bindParam(":username", $this->Username);
        $statement->bindParam(":educationID", $id);
        $statement->execute();
    }

    // Returns a string array of education institution names.
    function GetEducation(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("SELECT EducationInstitutions.Name FROM UserEducationLink JOIN EducationInstitutions ON UserEducationLink.EducationID = EducationInstitutions.ID WHERE Username = :username");
        $statement->bindParam(":username", $this->Username);

        $institutions = [];
        $count = 0;

        $result = $statement->execute();
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $institutions[$count++] = $row["Name"];
        }

        return $institutions;
    }

    /* #endregion */

    /* #region Work Experience */

    // Add a new work experience object to the user. Returns the resultant work experience object.
    function AddWorkExperience($workInstitutionName) : PortfolioWorkExperience{
        // Check if the institution exists...
        $id = GetWorkInstitution($workInstitutionName);
        if($id == -1)
            $id = RegisterWorkInstitution($workInstitutionName);

        // If the institution exists, add it.

        return PortfolioWorkExperience::CreateWorkExperience($this->Username, $id);
    }

    // Returns an array of PortfolioWorkExperience objects that are linked to this user.
    function GetWorkExperience(){
        $db = new SQLite3(SQLPATH);
        $statement = $db->prepare("SELECT ID FROM PortfolioWorkExperiences WHERE Username = :username");
        $statement->bindParam(":username", $this->Username);

        $experiences = [];
        $count = 0;

        $result = $statement->execute();
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $experiences[$count++] = PortfolioWorkExperience::GetWorkExperience($row["ID"]);
        }

        return $experiences;
    }

    /* #endregion */

    /* #region Artefacts */

    // Creates a new artefact attached to this account.
    function AddNewArtefact() : PortfolioArtefact{
        return PortfolioArtefact::CreateArtefact($this->Username);
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
    $result = $statement->execute()->fetchArray();

    // If query returns empty, return.
    if (!$result) {
        return false;
    }
    
    return password_verify($password, $result["PasswordHash"]);
}

/* #endregion */

/* #region Education Functions */

// Register a new education institution. Returns the ID of the new education institution.
function RegisterEducationInstitution($name) : INT{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("INSERT INTO EducationInstitutions (Name) VALUES (:name)");
    $statement->bindParam(":name", $name);
    $statement->execute();

    return $db->lastInsertRowid();
}

// Returns the ID of an education institution. Returns -1 if not found.
function GetEducationInstitution($name) : INT{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("SELECT ID FROM EducationInstitutions WHERE Name = :name");
    $statement->bindParam(":name", $name);
    $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);

    if(!$result)
        return -1;
    return $result["ID"];
}

/* #endregion */

/* #region Work Functions */

// Register a new work institution. Returns the ID of the new work institution.
function RegisterWorkInstitution($name) : INT{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("INSERT INTO WorkInstitutions (Name) VALUES (:name)");
    $statement->bindParam(":name", $name);
    $statement->execute();

    return $db->lastInsertRowid();
}

// Returns the ID of an work institution. Returns -1 if not found.
function GetWorkInstitution($name) : INT{
    $db = new SQLite3(SQLPATH);

    $statement = $db->prepare("SELECT ID FROM WorkInstitutions WHERE Name = :name");
    $statement->bindParam(":name", $name);
    $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);

    if(!$result)
        return -1;
    return $result["ID"];
}

/* #endregion */

// Initializes tables
function InitializeDatabase(){
    $db = new SQLite3(SQLPATH);
    // Create UserAccounts table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS UserAccounts (
        Username TEXT PRIMARY KEY,
        PasswordHash TEXT NOT NULL,
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
        Name TEXT NOT NULL
        )'
        );
    // Create WorkInstitutions Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS WorkInstitutions (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Name TEXT
        )'
        );
    // Create PortfolioArtefacts Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS PortfolioArtefacts (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Username TEXT NOT NULL,
        Title TEXT,
        Description TEXT,
        ThumbnailLink TEXT,
        FileLink TEXT,
        Tags TEXT
        )'
        );
    // Create PortfolioWorkExperiences Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS PortfolioWorkExperiences (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Username TEXT NOT NULL,
        WorkInstitutionID INTEGER NOT NULL,
        StartDate TEXT,
        EndDate TEXT,
        JobTitle TEXT,
        Description TEXT
        )'
        );
    // Create UserEducationLink Table
    $db->exec(
        'CREATE TABLE IF NOT EXISTS UserEducationLink (
        Username TEXT NOT NULL,
        EducationID INTEGER NOT NULL
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

    // Save the changes made to the fields.
    $newAccount->SaveChanges();

    // Add artefacts...
    for($i = 0; $i < 5; $i++){
        // Create a new object
        $artefact = $newAccount->AddNewArtefact();
        
        // Populate fields
        $artefact->Title = "Artefact ".($i+1);
        $artefact->Description = "This is artefact number ".($i+1).".";
        $artefact->ThumbnailLink = "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.thetimes.co.uk%2Farticle%2Frick-astley-the-internet-s-oldest-joke-is-having-the-last-laugh-kwksbq757&psig=AOvVaw2ENgG_QGvmQTUzZ9zN1FJu&ust=1648216198875000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCOCqt_nx3vYCFQAAAAAdAAAAABAD";
        $artefact->FileLink = "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.thetimes.co.uk%2Farticle%2Frick-astley-the-internet-s-oldest-joke-is-having-the-last-laugh-kwksbq757&psig=AOvVaw2ENgG_QGvmQTUzZ9zN1FJu&ust=1648216198875000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCOCqt_nx3vYCFQAAAAAdAAAAABAD";
        $artefact->Tags = ["Year ".$i+1, "University"];
        
        // Save the changes made to the fields.
        $artefact->SaveChanges();
    }

    // How to get all artefacts registered to the user.
    $artefacts = $newAccount->GetArtefacts();

    // Add education...
    $newAccount->AddEducation("A University");
    $newAccount->AddEducation("A School");

    // How to get all education institutes registered to the user.
    $education = $newAccount->GetEducation();

    // Add work experience...
    for($i = 0; $i < 5; $i++){
        // Create a new entry
        $workExperience = $newAccount->AddWorkExperience("Company ".($i+1));
        
        // Populate fields.
        $workExperience->StartDate = ($i*2+1)."/3/2022";
        $workExperience->EndDate = ($i*2+2)."/3/2022";
        $workExperience->JobTitle = "Worker number ".($i+1);
        $workExperience->Description = "I worked here.";

        // Save the changes made to the fields.
        $workExperience->SaveChanges();
    }

    // How to get all work experiences
    $workExperience = $newAccount->GetWorkExperience();
}
?>
