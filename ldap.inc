<?php
// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connection

function isMemberOfGroup($uniqname, $group) {
$ds=ldap_connect("ldap.umich.edu");  // must be a valid LDAP server!

  if ($ds) {
      $r=ldap_bind($ds);     // this is an "anonymous" bind, typically
                             // read-only access
      $dn="ou=Groups,dc=umich,dc=edu";
      $sr=ldap_search($ds, $dn, "(member=uid=$uniqname,ou=People,dc=umich,dc=edu)",["cn"]);
      $info = ldap_get_entries($ds, $sr);
      $member = false;
      for ($i=0; $i<$info["count"]; $i++) {
        if ($info[$i]["cn"][0] == $group) {
          $member = true;
          break;
        }
      }
      ldap_close($ds);
      return $member;
  } else {
     echo "<h4>Unable to connect to LDAP server</h4>";
  }
}

function userGroups($uniqname) {
  $ds=ldap_connect("ldap.umich.edu");
  if ($ds) {
    $r=ldap_bind($ds); 
    $dn="ou=Groups,dc=umich,dc=edu";
    $sr=ldap_search($ds, $dn, "(member=uid=$uniqname,ou=People,dc=umich,dc=edu)",["cn"]);
    $info = ldap_get_entries($ds, $sr);
    $groups = array();
    for ($i=0; $i<$info["count"]; $i++) {
      $groups[] = $info[$i]["cn"][0];
    }
  }
  return $groups; 
}

?>
