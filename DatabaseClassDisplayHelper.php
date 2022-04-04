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
                    <label>Job Title</label><br>
                    <input name="JobTitle" class="DatabaseClassBoxTitle" value="<?php echo $portfolioWorkExperience->JobTitle; ?>" /><br>
                    <input name="WorkInstitution" class="WorkExperienceInstitution" value="<?php echo $portfolioWorkExperience->GetWorkInstitutionName(); ?>" />
                    <input name="StartDate" type="date" value="<?php echo $portfolioWorkExperience->StartDate; ?>" />
                    <input name="EndDate" type="date" value="<?php echo $portfolioWorkExperience->EndDate; ?>" />
                    <label>Description</label><br>
                    <input name="Description" class="WorkExperienceDescription" value="<?php echo $portfolioWorkExperience->Description; ?>" /><br>
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
                    <label>Title</label><br>
                    <input name="Title" type="text" class="DatabaseClassBoxTitle" value="<?php echo $portfolioArtefact->Title; ?>" /><br>
                    <label>Thumbnail</label><br>
                    <img class="ArtefactBoxThumbnail" src="<?php echo $portfolioArtefact->ThumbnailLink; ?>" /><br>
                    <input name="Thumbnail" type="file" value="<?php echo $portfolioArtefact->ThumbnailLink; ?>" /><br>
                    <label>File</label><br>
                    <a href="<?php echo $portfolioArtefact->FileLink ?>">Current file</a><br>
                    <input name="File" type="file" value="<?php echo $portfolioArtefact->FileLink; ?>" /><br>
                    <input name="ID" type="hidden" value="<?php echo $portfolioArtefact->GetID(); ?>" />
                    <input name="ArtefactSave" type="submit" value="Save Changes" />
                    <!-- <input name="ArtefactDelete" type="submit" value="Delete" /> -->
                </form>
            <?php } ?>
        </div>
        <?php
    }
?>