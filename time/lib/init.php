<?php
include(ALLOC_MOD_DIR."/time/lib/timeUnit.inc");
include(ALLOC_MOD_DIR."/time/lib/timeSheet.inc");
include(ALLOC_MOD_DIR."/time/lib/timeSheetItem.inc");
include(ALLOC_MOD_DIR."/time/lib/pendingApprovalTimeSheetListHomeItem.inc");

class time_module extends module
{
  var $db_entities = array("timeSheet", "timeSheetItem");

  function register_toolbar_items() {
    global $current_user;

    if (isset($current_user) && $current_user->is_employee()) {
      register_toolbar_item("timeSheetList", "Time Sheets");
    }
  }

  function register_home_items() {
    global $current_user;

    if (isset($current_user) && $current_user->is_employee()) {
      include(ALLOC_MOD_DIR."/time/lib/timeSheetListHomeItem.inc");
      register_home_item(new timeSheetListHomeItem);
      if (has_pending_timesheet()) {
        register_home_item(new pendingApprovalTimeSheetListHomeItem);
      }
    }
  }

}






?>
