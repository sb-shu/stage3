<?php
    include_once("DatabaseInterface.php");
    // Takes a work experience array and generates html for a timeline.
    function GenerateWorkExperienceTimeline($portfolioWorkExperienceArray){

        // TODO: Sort the array by date, new to old.

        ?>
        <div class="TimelineContainer">
            <?php 
                // Generate an item for every element in the array
                foreach($portfolioWorkExperienceArray as $workExperience){ 
            ?>

            <div class="TimelineItem">
                <p class="TimelineTitle"><?php echo $workExperience->JobTitle; ?></p>
                <p class="TimelineInstitution">at <?php echo $workExperience->GetWorkInstitutionName(); ?></p>
                <p><?php echo $workExperience->StartDate." - ".$workExperience->EndDate; ?></p>
                <p class="TimelineDescription"><?php echo $workExperience->Description; ?></p>
            </div>

            <?php } ?>
        </div>
        
        <?php
    }
?>