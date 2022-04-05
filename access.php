<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');

function is_admin($uniqname)
{
        global $other_admins;
        return array_search($uniqname, $other_admins) !== FALSE;
}

function no_access($valid_users, $valid_groups, $user) {
  if (in_array($user, $valid_users) == FALSE) {
    $user_membership = 'no access';
  }
  if (count(array_intersect($valid_groups, $_SESSION['user_membership'])) == 0) {
    $group_membership = 'no access';
  }
  if ($user_membership == 'no access' && $group_membership == 'no access') { 
echo "<br>";
    echo 'no access';
echo "<br>";
    exit;
  }
}

include('access_list.inc');
//var_export($_SESSION['user_membership']);
$current_user = $_SESSION['attributes']['sub'];
//echo"<br>user: ";
//echo $current_user;
no_access($valid_users, $valid_groups, $current_user);
?>
