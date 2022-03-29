<?php
    include_once("DatabaseInterface.php");
    
    function GenerateWorkExperienceBox($portfolioWorkExperience){
        ?>
        <div class="DatabaseClassBox WorkExperienceBox">
            <p class="DatabaseClassBoxTitle"><?php echo $portfolioWorkExperience->JobTitle; ?></p>
            <p class="WorkExperienceInstitution">at <?php echo $portfolioWorkExperience->GetWorkInstitutionName(); ?></p>
            <p><?php echo $portfolioWorkExperience->StartDate." - ".$portfolioWorkExperience->EndDate; ?></p>
            <p class="WorkExperienceDescription"><?php echo $portfolioWorkExperience->Description; ?></p>
        </div>
        <?php
    }

    // Takes a work experience array and generates html for a timeline.
    function GenerateWorkExperienceTimeline($portfolioWorkExperienceArray){

        // TODO: Sort the array by date, new to old.

        ?>
        <div class="WorkExperienceTimeline">
            <?php 
                // Generate an item for every element in the array
                foreach($portfolioWorkExperienceArray as $workExperience){
                    GenerateWorkExperienceBox($workExperience);
                }
            ?>
        </div>
        <?php
    }

    function GenerateArtefactBox($portfolioArtefact){
        ?>
        <a href=<?php echo $portfolioArtefact->FileLink; ?> class="DatabaseClassBox ArtefactBox">
            <p class="DatabaseClassBoxTitle"><?php echo $portfolioArtefact->Title; ?></p>
            <img class="ArtefactBoxThumbnail" src=<?php echo $portfolioArtefact->ThumbnailLink; ?>>
        </a>
        <?php
    }
?>