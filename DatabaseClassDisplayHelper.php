<?php
    include_once("DatabaseInterface.php");
    
    function GenerateWorkExperienceBox($portfolioWorkExperience, $enableEditButtons = false){
        ?>
        <div class="DatabaseClassBox WorkExperienceBox">
            <?php if(!$enableEditButtons) { ?>
                <p class="DatabaseClassBoxTitle"><?php echo $portfolioWorkExperience->JobTitle; ?></p>
                <p class="WorkExperienceInstitution">at <?php echo $portfolioWorkExperience->GetWorkInstitutionName(); ?></p>
                <p><?php echo $portfolioWorkExperience->StartDate." - ".$portfolioWorkExperience->EndDate; ?></p>
                <p class="WorkExperienceDescription"><?php echo $portfolioWorkExperience->Description; ?></p>
            <?php } else { ?>
                <form>
                    <input value=<?php echo $portfolioWorkExperience->JobTitle; ?>>
                    <input value=<?php echo $portfolioWorkExperience->GetWorkInstitutionName(); ?>>
                    <input type="date" value=<?php echo $portfolioWorkExperience->StartDate; ?>>
                    <input type="date" value=<?php echo $portfolioWorkExperience->EndDate; ?>>
                    <input value=<?php echo $portfolioWorkExperience->Description; ?>>
                    <submit>Save Changes</submit>
                    <submit>Delete</submit>
                </form>
            <?php } ?>
        </div>
        <?php
    }

    // Takes a work experience array and generates html for a timeline.
    function GenerateWorkExperienceTimeline($portfolioWorkExperienceArray, $enableEditButtons = false){

        // TODO: Sort the array by date, new to old.

        ?>
        <div class="WorkExperienceTimeline">
            <?php 
                // Generate an item for every element in the array
                foreach($portfolioWorkExperienceArray as $workExperience){
                    GenerateWorkExperienceBox($workExperience,$enableEditButtons);
                }
            ?>
        </div>
        <?php
    }

    function GenerateArtefactBox($portfolioArtefact, $enableEditButtons = false){
        ?>
        <div class="DatabaseClassBox ArtefactBox">
            <?php if(!$enableEditButtons) { ?>
                <a href=<?php echo $portfolioArtefact->FileLink; ?>>
                    <p class="DatabaseClassBoxTitle"><?php echo $portfolioArtefact->Title; ?></p>
                    <img class="ArtefactBoxThumbnail" src=<?php echo $portfolioArtefact->ThumbnailLink; ?>>
                </a>
            <?php } else { ?>
                <form>
                    <input value=<?php echo $portfolioArtefact->Title; ?>>
                    <input value=<?php echo $portfolioArtefact->FileLink; ?>>
                    <input value=<?php echo $portfolioArtefact->ThumbnailLink; ?>>
                    <submit>Save Changes</submit>
                    <submit>Delete</submit>
                </form>
            <?php } ?>
        </div>
        <?php
    }
?>