<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
include('.access_list.inc');

function is_admin($uniqname, $other_admins)
{
        return array_search($uniqname, $other_admins) !== FALSE;
}

function check_access($valid_users, $valid_groups, $user, $other_admins = array(), $admin_access = false) {
  if (in_array($user, $valid_users) == FALSE) {
    $user_access = false;
  } else {
    $user_access = true;
  }

  if (count(array_intersect($valid_groups, $_SESSION['user_membership'])) == 0) {
    $group_access = false;
  } else {
    $group_access = true;
  }

  if ($admin_access) {
   if(($user_access || $group_access) && is_admin($user, $other_admins)) {
     return true;
   } else {
    return false;
   }
  } else {
    if ($user_access || $group_access) { 
      return true;
    } else {
      return false;
    }
  }
}

function admin_access () {
 $current_user = $_SESSION['current_user'];
 global $valid_users;
 global $valid_groups;
 global $other_admins;
 if (check_access($valid_users, $valid_groups, $current_user, $other_admins, true)) {
   return true;
 } else {
   return false;
 }
}
function non_admin_access () {
 $current_user = $_SESSION['current_user'];
 global $valid_users;
 global $valid_groups;
 if (check_access($valid_users, $valid_groups, $current_user)) {
   return true;
 } else {
   return false;
 }
}
?>
