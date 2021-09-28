<?php
re_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

   
    $id = $purifier->purify($_REQUEST['id']);
    $result = mysqli_query($conn, "DELETE FROM eligibility_list WHERE id =$id")  or die(mysqli_error($conn));

     header("Location: edit_eligibility.php");  
}

?>
