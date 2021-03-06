<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Chemistry Awards System - University of Michigan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META content="" name=KEYWORDS>
<META content="" name=description>

<link rel="stylesheet" href="../eebstyle.css">

</head>


<body>

</div>
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
require_once('nav.php');
?>

<?php

if (isset($_REQUEST['edit_record']) && $_REQUEST['edit_record'] == "Save changes")
{

  $id = $purifier->purify($_REQUEST['id']);
  $uniqname1 = $purifier->purify($_REQUEST['uniqname']);
//   $uniqname = $_SERVER["REDIRECT_REMOTE_USER"];
   $uniqname = $_SESSION["current_user"];
 if ($uniqname == $uniqname1)  {
  $Name = $purifier->purify($_REQUEST['Name']);
  $Rank = $purifier->purify($_REQUEST['Rank']);

  $birth_year = $purifier->purify($_REQUEST['birth_year']);
  $Appt_Start = $purifier->purify($_REQUEST['Appt_Start']);
  $Year_Tenured = $purifier->purify($_REQUEST['Year_Tenured']);
  $Year_Promoted = $purifier->purify($_REQUEST['Year_Promoted']);

if ($id !== "") {
  $sql = "UPDATE faculty SET
      uniqname = '$uniqname',
      Name = '$Name',
      Rank = '$Rank',
      birth_year = '$birth_year',
      Appt_Start = '$Appt_Start',
      Year_Tenured = '$Year_Tenured',
      Year_Promoted = '$Year_Promoted'
      WHERE id ='$id'";
}
else {
  // add a new record
 $sql = "INSERT INTO `faculty`(`uniqname`, `Name`, `Rank`, `birth_year`, `Appt_Start`, `Year_Tenured`, `Year_Promoted`) VALUES ('$uniqname', '$Name', '$Rank', '$birth_year', '$Appt_Start', '$Year_Tenured', '$Year_Promoted')";
}
  if (mysqli_query($conn, $sql)) { 
     if ($id == "") {
       // record was added not updated
      $id = mysqli_insert_id($conn);
   }
// add clusters
         $cluster_check = array();
   if (isset($_REQUEST['cluster_check'])) { 
    $cluster_check = purica_array($conn, $_REQUEST['cluster_check']);
   }
// echo '<pre>'; var_export($cluster_check); echo '</pre>';
    $clusterlist = array();
    $clusterlist = purica_array($conn, $_REQUEST['clusterlist']);
// echo '<pre>'; var_export($clusterlist); echo '</pre>';
      if (!empty($cluster_check)) {
       // clusters
       $sqlcluster = "INSERT INTO faculty_cluster (`faculty_id`, `cluster_id`) VALUES";
       foreach ($cluster_check as $cluster_id) {
          $sqlcluster .= " (" . $id . ", " . $cluster_id . "),";
       }
       $sqlcluster = substr($sqlcluster, 0, -1);
       $sqlcluster .= " ON DUPLICATE KEY UPDATE faculty_id = " . $id;
       $res = mysqli_query($conn, $sqlcluster) or die("There was an error 2 updating cluster: ".mysqli_error($conn));
      // to delete unchecked
    if (count($cluster_check) !== (count($clusterlist))) { 
      $sqldel = "DELETE FROM faculty_cluster WHERE";
      foreach ($clusterlist as $cluster1) {
         if (!in_array($cluster1, $cluster_check)) {
            $sqldel .= "(faculty_id = " . $id . " and cluster_id = " . $cluster1 . ") or ";
         }
       }
      $sqldel = substr($sqldel, 0, -4);
      $res = mysqli_query($conn, $sqldel) or die("There was an error deleting cluster: ".mysqli_error($conn));
    }
     }
     else {
      $sqldel = "DELETE FROM faculty_cluster WHERE faculty_id = $id";
      $res = mysqli_query($conn, $sqldel) or die("There was an error 1 updating cluster: ".mysqli_error($conn));
     }
    echo "The record has been updated";
  }
  else {
    die("There was an error updating a record: ".mysqli_error($conn));
  }
 } else {
   echo "You are supposed to change only your own information";
 }
}

//$uniqname = $_SERVER["REMOTE_USER"]; 
//$uniqname = $_SERVER["REDIRECT_REMOTE_USER"]; 
$uniqname = $_SESSION['current_user'];

	//Everything is peachy, pull record.

$sql = "SELECT faculty.`id`, `uniqname`, `Name`, faculty.`Rank`, rank.rank as rank, `birth_year`, `Appt_Start`, `Year_Tenured`, `Year_Promoted` FROM `faculty`, rank  WHERE rank.id = faculty.Rank AND faculty.uniqname = '$uniqname'";
//echo $sql;
	$result=mysqli_query($conn, $sql) or die("There was an error: ".mysqli_error($conn));


	$adata = mysqli_fetch_array($result, MYSQLI_BOTH);
        $id = $adata['id'];
  $birth_year = $adata['birth_year'];
  $Appt_Start = $adata['Appt_Start'];
  $Year_Tenured = $adata['Year_Tenured'];
  $Year_Promoted = $adata['Year_Promoted'];
?>	
    <form name="form" method="post" action="faculty.php">
	<div align="center"><img src="../images/linecalendarpopup500.jpg"></div><br>
<table>
<INPUT type ='hidden' name='id' value='<?php echo $id; ?>'>
<tr><th>Uniqname:<td><input type="text" name="uniqname" value="<?php print($adata['uniqname']) ?>" >
<tr><th>Name:<td><input type="text" name="Name" value="<?php print($adata['Name']) ?>" >
<tr><th>Rank:<td> 
<?php
$rank = $adata['rank'];
$sqlrank = "SELECT id, rank FROM rank";
$resultrank = mysqli_query($conn, $sqlrank) or header('Location: ERROR.php?error="Unable to select applicant\'s information for editing."');
echo "<select name='Rank'>";
if (mysqli_num_rows($resultrank) != 0) {
     while ( $ranks = mysqli_fetch_array($resultrank, MYSQLI_BOTH) ) {
           echo "<option";
           if ($ranks['rank'] == $rank) { echo " selected"; }
           echo " value=$ranks[id]>$ranks[rank]</option>";
     }
     echo "</select>";
}
?> 

<tr><th>Birth Year:<td><input type="text" name="birth_year" maxlength="4" value="<?php print($birth_year) ?>">
<tr><th>Appt Start:<td><input type="text" name="Appt_Start" maxlength="4" value="<?php print($Appt_Start) ?>">
<tr><th>Year Tenured:<td><input type="text" name="Year_Tenured" maxlength="4" value="<?php print($Year_Tenured) ?>">
<tr><th>Year Promoted:<td><input type="text" name="Year_Promoted" maxlength="4" value="<?php print($Year_Promoted) ?>">
<tr><th>Clusters:<td>
<?php
$sqlclusterids = "SELECT clusters.id FROM clusters INNER JOIN faculty_cluster ON clusters.id = faculty_cluster.cluster_id WHERE faculty_id = '$id'";
$resultcluster_list = mysqli_query($conn, $sqlclusterids) or header('Location: ERROR.php?error="Unable to select clusters."');
$clustersids = array();
while ($cluster1 = mysqli_fetch_array ($resultcluster_list, MYSQLI_BOTH)) {
   $clustersids[] = $cluster1['id'];
}
$sqlcluster = "SELECT id, clusters.name FROM clusters";
$resultcluster = mysqli_query($conn, $sqlcluster) or header('Location: ERROR.php?error="Unable to select clusters."');
if (mysqli_num_rows($resultcluster) != 0) {
     while ( $clusters = mysqli_fetch_array($resultcluster, MYSQLI_BOTH) ) {
           echo "<input type='checkbox' name='cluster_check[";
           echo $clusters['id'];
           echo "]' ";
           echo "value='$clusters[id]'";
           if (in_array($clusters['id'], $clustersids)) {echo " checked"; }
           echo ">$clusters[name]";
           echo "<input type='hidden' name='clusterlist[]' value='" . $clusters['id'] . "'>";
     }
}
?>

</table>
			
        <br><div align="center"><INPUT type="submit" name="edit_record" value="Save changes" style="width:1 00px; height: 50px;">
</form></div>
<div align="center"><img src="../images/linecalendarpopup500.jpg"></div><br>
<table>
<?php
$sql1 = "SELECT id AS letter_id, type, link, upload_date FROM faculty_letters WHERE uniqname = '$uniqname' AND type = 'cv'";
$result1 = mysqli_query($conn, $sql1) or die ("Query failed : " . mysqli_error($conn));
WHILE ($recUpload = mysqli_fetch_array($result1, MYSQLI_BOTH))
        { ?>
              <tr><td> <?php print("$recUpload[type]") ?> :</td><td>
                 <?php $link = '../uploadfiles/' . $recUpload['link'];
                   print("<a href=". $link . " target=\"_blank\"> $recUpload[link]</a>") ?><br>
              <td> <?php print("$recUpload[upload_date]") ?></td>

                <?php
                 $letter_id = $recUpload['letter_id'];
            echo '<td>';
echo "<form name='form3' action='delete_file.php' method='post'>";

echo '<input type="hidden" name="id" value="' . $letter_id . '">';
echo ('<input type="submit" name="delete" value="Delete" onclick="return confirm(\'Are you sure to delete this file?\')">');
            echo '</form></td>';
        }//while
?>
</table>

	<div align="center"><img src="../images/linecalendarpopup500.jpg"></div><br>

<?php
if (isset($id)) {
$sqlf = "SELECT faculty_awards.award_id as award_id, faculty_awards.faculty_id AS faculty_id, faculty.Name, award_status.`status`, `year`, `comment`, awards_descr.Award_Name FROM `faculty_awards`JOIN faculty ON faculty_awards.faculty_id = faculty.id JOIN awards_descr ON faculty_awards.award_id = awards_descr.id, award_status WHERE faculty_awards.status = award_status.id AND faculty_id = $id ORDER BY year, award_status.status";
//echo $sqlf;
$resultf = mysqli_query($conn, $sqlf) or die("Query failed :".mysqli_error($conn));

if (mysqli_num_rows($resultf) != 0) {
echo "<table>";
echo "<th>Id<th>Award Name<th>year<th>status<th>Comments</tr>";
     while ( $faward = mysqli_fetch_array($resultf, MYSQLI_BOTH) ) {
         $status = $faward['status'];
         $faculty_id = $faward['faculty_id'];
         $year = $faward['year'];
//           echo"<tr><td><a href='award-one.php?id=$faward[award_id]'>$faward[Award_Name]</a></td>";
           echo"<tr><td>$faward[award_id]</td>";
           echo"<td>$faward[Award_Name]</td>";
           echo "<td>" . $year. "</td>";
           echo "<td>" . $status . "</td>";
           echo "<td>" . $faward['comment']. "</td>";
          echo "</td>";
     }

}
}

?>
<table>
	<div align="center"><br><img src="../images/linecalendarpopup500.jpg"></div><br>
<div class="clear"></div>


</div>   
</div> 
</body>
</html>

