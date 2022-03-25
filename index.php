<?php
  // Include methods for database usage.
  include_once("DatabaseInterface.php");
  $user = GetUser("testuser");
  $artefacts = $user->GetArtefacts();
?>

<html>
<head>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="split left">
  <div class="top">
    <img src="Donny.webp" alt="Profile Picture">
    <h2>Name: Jane Flex</h2>
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
          var_dump($artefacts);
          echo $artefacts[0]->Title;
          echo $artefacts[1]->Description;
        ?>
    <h3 id="year2">Year 2</h2>

    <h3 id="year3">Year 3</h2>

    <h3 id="year4">Year 4</h2>
   
    <h2 id="work" >Work Experience</h2>
    <h3>Lawyer</h3>
    <h3>Sept 2012 - March 2018</h3>
    <p>Design and build advanced applications for the Android platform
Collaborate with cross-functional teams to define, design, and ship new features
Work with outside data sources and APIs
Unit-test code for robustness, including edge cases, usability, and general reliability
Work on bug fixing and improving application performance
Continuously discover, evaluate, and implement new technologies to maximize development efficiency </p>

    <h3>Software Developer</h3>
    <h3>July 2019 - Oct 2021</h3>
    <p>Participate in the entire application lifecycle, focusing on coding and debugging 
Write clean code to develop functional web applications
Troubleshoot and debug applications
Perform UI tests to optimize performance
Manage cutting-edge technologies to improve legacy applications
Collaborate with Front-end developers to integrate user-facing elements with server side logic
Gather and address technical and design requirements
Provide training and support to internal teams
Build reusable code and libraries for future use
Liaise with developers, designers and system administrators to identify new features
Follow emerging technologies </p>

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
