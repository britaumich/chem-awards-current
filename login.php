<?php
session_start();
$_SESSION['login'] = false;
require 'vendor/autoload.php';
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
require_once('ldap.inc');

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

$auth = $oidc->authenticate();
if ($auth) {
  $_SESSION['login'] = true;
  $_SESSION['current_user'] = $oidc->requestUserInfo('sub');
  $current_user = $_SESSION['current_user'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title> Chemistry Awards</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LSA-Chemistry Chemistry Awards">
    <meta name="keywords" content="LSA-Chemistry, Chem-Awards, UniversityofMichigan">
    <meta name="author" content="LSA-MIS_brita">
    <link rel="icon" href="images/favicon.ico">
    <script type="text/javascript" src="js/modernizr-latest.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous">
    </script>
    <script
      src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"
      integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY="
      crossorigin="anonymous">
    </script>
  </head>
  <body>
    <div id="spinner" style="position:fixed;top:50%;left:50%;display:none;">
      <img src="images/spinning_segments.gif" alt="Loading">
    </div>

<?php
echo '<script type="text/JavaScript"> 
$("#spinner").show();
</script>';

$groups = userGroups($current_user);
$_SESSION['user_membership'] = $groups;
forceRedirect('index.php');
?>
  </body>
</html>
