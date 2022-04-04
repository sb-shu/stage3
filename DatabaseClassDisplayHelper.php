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
                <form method="POST" action="profileEdit.php">
                    <input name="JobTitle" class="DatabaseClassBoxTitle" value="<?php echo $portfolioWorkExperience->JobTitle; ?>" />
                    <!-- <input name="WorkInstitution" class="WorkExperienceInstitution" value="<?php echo $portfolioWorkExperience->GetWorkInstitutionName(); ?>" />
                    <input name="StartDate" type="date" value="<?php echo $portfolioWorkExperience->StartDate; ?>" />
                    <input name="EndDate" type="date" value="<?php echo $portfolioWorkExperience->EndDate; ?>" /> -->
                    <input name="Description" class="WorkExperienceDescription" value="<?php echo $portfolioWorkExperience->Description; ?>" />
                    <input name="ID" type="hidden" value="<?php echo $portfolioWorkExperience->GetID(); ?>" />
                    <input name="WorkExperienceSave" type="submit" value="Save Changes" />
                    <!-- <input name="WorkExperienceDelete" type="submit" value="Delete" /> -->
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
                    <img class="ArtefactBoxThumbnail" src=<?php echo $portfolioArtefact->ThumbnailLink; ?> />
                </a>
            <?php } else { ?>
                <form method="POST" action="profileEdit.php">
                    <input name="Title" type="text" class="DatabaseClassBoxTitle" value="<?php echo $portfolioArtefact->Title; ?>" />
                    <input name="FileLink" type="text" value="<?php echo $portfolioArtefact->FileLink; ?>" />
                    <input name="ThumbnailLink" type="text" value="<?php echo $portfolioArtefact->ThumbnailLink; ?>" />
                    <input name="ID" type="hidden" value="<?php echo $portfolioArtefact->GetID(); ?>" />
                    <input name="ArtefactSave" type="submit" value="Save Changes" />
                    <!-- <input name="ArtefactDelete" type="submit" value="Delete" /> -->
                </form>
            <?php } ?>
        </div>
        <?php
    }
?>