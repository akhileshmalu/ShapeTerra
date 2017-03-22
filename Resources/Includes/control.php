<?php

// For Contributors & Team Leads
if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>

    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">

    <button type="submit" id="submit_approve" name="submit_approve"
        <?php if ($rowsbpstatus['CONTENT_STATUS'] == 'Not Started') echo 'disabled'; ?>
            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">Submit For Approval
    </button>

    <button id="save" type="submit" name="savedraft"
            onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
            class="btn-secondary col-lg-3 col-md-7 col-sm-8">
        Save Draft
    </button>


    


<?php
// For Deans & Designee
} elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>
    <button id="save" type="submit" name="savedraft"
            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
        Save Draft
    </button>
    <input type="button" id="cancelbtn" value="Cancel & Discard"
           class="btn-primary cancelbox pull-left">

    <?php
    // For Dean Approval

    if ($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
        <input type="submit" id="approve" name="approve" value="Approve" class="btn-primary pull-right">
        <input type="submit" id="reject" name="reject" value="Reject" class="btn-primary pull-right">
    <?php endif;
} ?>