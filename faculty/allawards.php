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
<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
require_once('nav.php');

//$sort = check_input($conn, $_REQUEST['sort']); 

$sqls = "SELECT a.`id`, a.`type`, a.`Award_Name`, a.Due_Month, a.`Awarded_By`, a.`Link_to_Website`, a.`Description`, a.`eligibility`, a.who_is_eligible, a.`comments`";
$from = " FROM `awards_descr` a";
$where = " WHERE 1";   
$sort = "FIELD(due_month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
$sqlsearch = $sqls . $from . $where; 
if ($sort !== '') {
   $sqlsearch .= " ORDER BY " . $sort; 

}
$start = "none";
$end = "none";

if (isset($_REQUEST['submit'])) {

     if(isset($_REQUEST['type'])) {
       $type = mysqli_real_escape_string($conn, $_REQUEST['type']);
     } else {
       $type = '';
     }
//     $due_month = check_input($conn, $_REQUEST['month']);
  if(isset($cluster)){
     $cluster = mysqli_real_escape_string($conn, $_REQUEST['cluster']);
  } else {
     $cluster = "";
  }

   if (isset($_REQUEST['start'])) {
     $start = mysqli_real_escape_string($conn, $_REQUEST['start']);
   }
   if (isset($_REQUEST['end'])) {
     $end = mysqli_real_escape_string($conn, $_REQUEST['end']);
   }
     $keyword_search = mysqli_real_escape_string($conn, $_REQUEST['keyword_search']);

    $cluster_check = array();
   if (isset($_REQUEST['cluster_check'])) {
    $cluster_check = purica_array($conn, $_REQUEST['cluster_check']);
   }
    if (!empty($cluster_check)) {
        $clusterlist = implode(", ", $cluster_check);
            $from = " FROM (SELECT `id`, `type`, `Award_Name`, Due_Month, `Awarded_By`, `Link_to_Website`, `Description`, `eligibility`, who_is_eligible, `comments` FROM  `awards_descr` JOIN award_cluster ON awards_descr.id = award_cluster.award_id WHERE award_cluster.cluster_id IN (" . $clusterlist . ") GROUP BY awards_descr.id) a ";
/*
        if ($tag !== 'none' ) {
          $from .= " JOIN award_tag ON a.id = award_tag.award_id ";
          $where = " WHERE award_tag.tag_id = " . $tag;
       }
*/
     }
/*
     elseif ($tag !== 'none' ) { 
        $from = " FROM (SELECT `id`, `type`, `Award_Name`, Due_Month, `Awarded_By`, `Link_to_Website`, `Description`, `eligibility`, who_is_eligible, `comments` FROM  `awards_descr` JOIN award_tag ON awards_descr.id = award_tag.award_id WHERE award_tag.tag_id = " . $tag . ") a "; 
     }
*/
     else { 
         $where = ' WHERE 1'; 
       
     }

     if ($type !== 'none' ) { $where .= " AND type = '" . $type . "'"; }
//     if ($eligable !== 'none' ) { $where .= " AND a.who_is_eligible = '" . $eligable . "'"; }
     if ($keyword_search !== "") { 
           $where .= " AND ("; 
         foreach (explode(",", $keyword_search) as $key) {
           $key = trim($key);
           $where .= " (Award_Name LIKE '%" . $key . "%') OR (Awarded_By LIKE '%" . $key . "%') OR"; 
         }      
         $where = substr($where, 0, -2);
         $where .= ")";

     }
//     $where .= "  AND due_month LIKE '%$due_month%'";

     $sqlsearch =  $sqls . $from . $where . " ORDER BY FIELD(due_month, 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August')";
//echo $sqlsearch;
//exit;
}
?>
<div class='floatleft'>
<?
    echo "<form name='form2' method='post' action='allawards.php'>";
    $sql = "SELECT DISTINCT type FROM awards_descr";
    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
    echo "Type: ";
    echo "<select name='type'>";
        echo "<option select value='none'> - choose type -</option>";
        if(isset($_REQUEST['type'])) {
          $type = mysqli_real_escape_string($conn, $_REQUEST['type']);
        } else {
          $type = '';
        }
        while ($typelist = mysqli_fetch_array($result, MYSQLI_BOTH))
        {
           echo "<option";
           if ($typelist['type'] == $type) { echo " selected"; } 
           echo " value=$typelist[type]>$typelist[type]</option>";
        }
    echo "</select><br>";

// month
//echo "<br>Award Month: ";
//$month = check_input($conn, $_REQUEST['month']);
/*
if ($month == "" ) { $month = "%";}
    $sqlm ="SELECT DISTINCT due_month FROM `awards_descr` order by month(str_to_date(left(due_month, 3),'%b'))";
      $resm = mysqli_query($conn, $sqlm) or die("There was an error getting min date: ".mysqli_error($conn));
echo "<select name='month'>";
echo "<option select value='%'> - pick all  -</option>";
while ($months = mysqli_fetch_array($resm, MYSQLI_BOTH)) {
           echo "<option";
           if ($months[due_month] == $month) { echo " selected"; }
           echo " value='$months[due_month]'>$months[due_month]</option>";
}
echo "</select>";
*/
//
/*
$sqlm = "SELECT DISTINCT due_month, if ( month(str_to_date(left(due_month, 3),'%b')) > 8, month(str_to_date(left(due_month, 3),'%b')), month(str_to_date(left(due_month, 3),'%b'))+12) AS num FROM awards_descr ORDER BY FIELD(due_month, 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'varied')";
//echo $sqlm;
    $resultm = mysqli_query($conn, $sqlm) or die("Query failed :".mysqli_error($conn));
echo "<br>Month Between: ";

if ($start == "none" ) {
    $sqls ="SELECT min(if ( month(str_to_date(left(due_month, 3),'%b')) > 8, month(str_to_date(left(due_month, 3),'%b')), month(str_to_date(left(due_month, 3),'%b'))+12)) AS min, min(due_month) as month from awards_descr where due_month <> 'varied' ORDER BY FIELD(due_month, 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August')";
//echo $sqls;
      $ress = mysqli_query($conn, $sqls) or die("There was an error getting min date: ".mysqli_error($conn));
     $start = mysqli_fetch_array($ress, MYSQLI_BOTH)['min'];
}
    echo "<select name='start'>";

       while ($months = mysqli_fetch_array($resultm, MYSQLI_BOTH))
        {
           echo "<option";
           if ($months[num] == $start) { echo " selected"; }
           echo " value='$months[num]'>$months[due_month]</option>";
        }
    echo "</select>";

echo " and: ";
    $resultm = mysqli_query($conn, $sqlm) or die("Query failed :".mysqli_error($conn));
if ($end == "none" ) {
    $sqls ="SELECT max(if ( month(str_to_date(left(due_month, 3),'%b')) > 8, month(str_to_date(left(due_month, 3),'%b')), month(str_to_date(left(due_month, 3),'%b'))+12)) AS max, max(due_month) as month from awards_descr where due_month <> 'varied' ORDER BY FIELD(due_month, 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August')";
      $ress = mysqli_query($conn, $sqls) or die("There was an error getting max date: ".mysqli_error($conn));
     $end = mysqli_fetch_array($ress, MYSQLI_BOTH)['max'];
}
    echo "<select name='end'>";

       while ($months = mysqli_fetch_array($resultm, MYSQLI_BOTH))
        {
           echo "<option";
           if ($months[num] == $end) { echo " selected"; }
           echo " value='$months[num]'>$months[due_month]</option>";
        }
    echo "</select>";
*/
// mult clusters
    echo "<br><br>Clusters: ";

$sql = "SELECT id, name FROM clusters ORDER BY id";
if(!isset($clusterlist)){
  $clusterlist = '';
}

$clustersids = array();
       $clustersids = explode(", ", $clusterlist);

//echo '<pre>all id'; var_export($clustersids); echo '</pre>';
    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
if (mysqli_num_rows($result) != 0) {
     while ( $clusters = mysqli_fetch_array($result, MYSQLI_BOTH) ) {
           $cname = $clusters['name'];
           echo "<input type='checkbox' name='cluster_check[";
           echo $clusters['id'];
           echo "]' ";
           echo "value='$clusters[id]'";
           if (in_array($clusters['id'], $clustersids)) {echo " checked"; }
                echo ">$cname";

//           echo ">$cname";
           echo "<input type='hidden' name='clusterlist[]' value='" . $clusters['id'] . "'>";
     }
}

/*
    $sql = "SELECT id, tag FROM tags ORDER BY tag";
    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
    echo "<br><br>Tags: ";
    echo "<select name='tag'>";
        echo "<option select value='none'> - choose  -</option>";
        while ($tags = mysqli_fetch_array($result, MYSQLI_BOTH))
        {
           echo "<option";
           if ($tags[id] == $tag) { echo " selected"; } 
           echo " value=$tags[id]>$tags[tag]</option>";
        }
    echo "</select>";
*/
//    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search by Keywords (in Award Name and Awarded By)";
if(!isset($keyword_search)){
 $keyword_search = "";
}
    echo "<br><br>Search by Keywords (in Award Name and Awarded By)";
    echo '&nbsp;<input type="text" name="keyword_search" size = "40" placeholder="-- keywords, separated by commas --" value="' . $keyword_search . '" >';
    echo "<br>";
/*
    $sql = "SELECT id, name FROM eligibility_list ORDER BY name";
    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
    echo "<br>Eligibility: ";
    echo "<select name='eligable'>";
        echo "<option select value='none'> - choose  -</option>";
        while ($eligibility = mysqli_fetch_array($result, MYSQLI_BOTH))
        {
           echo "<option";
           if ($eligibility[id] == $eligable) { echo " selected"; } 
           echo " value=$eligibility[id]>$eligibility[name]</option>";
        }
    echo "</select>";
*/
    echo "<br><br>";
?>
       <input type="submit" name="submit" value="Search">
</form>
<?php 
//echo "<br>";
//echo $sqlsearch;
 $result = mysqli_query($conn, $sqlsearch) or die("sqlsearch query failedb:".mysqli_error($conn));
$total=mysqli_num_rows($result);
     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<Strong>Total:</Strong> ".$total;
echo "<Br><Br>";
 //show table headers for results
echo "</div>";
echo "<div class='NewLine'>";

echo "<table>";
 
echo ("
<tr>
	<th>Award Details</th>
	<th>Award Name (external link)</a></th>
	<th>Due Month</a></th>
	<th>Awarded By</a></th>
	<th>Brief Description</th>
	<th>Eligibility</th>

</tr>
");
// array for list of id
$search_id_list = array();
while ( $idata = mysqli_fetch_array($result, MYSQLI_BOTH) ) 
{
	
// get a list of ids from $sqlsearch
     $search_id_list[] = $idata['id'];
}
 $result = mysqli_query($conn, $sqlsearch) or die("sqlsearch query failedb:".mysqli_error($conn));

while ( $adata = mysqli_fetch_array($result, MYSQLI_BOTH) ) 
{
	
	echo ("<tr>");
		
echo "<form name='form3' action='award.php' method='post'>";
     $arr = serialize($search_id_list);
     echo "<input type='hidden' name='search_id_list' value='" . $arr . "'>"  ;

echo '<input type="hidden" name="award_id" value="' . $adata['id'] . '">';
echo ('<td> <input type="submit" name="List" value="Open"></td>');
 echo('</form></td>');

		echo "<td><a href='$adata[Link_to_Website]' target='_blank'>$adata[Award_Name]</td>";
		echo "<td>$adata[Due_Month]</td>";
		echo "<td>$adata[Awarded_By]</td>";
   $aname = $adata['Award_Name'];
    $descr = $adata['Description'];
    $elig = $adata['eligibility'];
    $descr = preg_replace("/\r?\n/", "\\n", addslashes($descr));
    $elig = preg_replace("/\r?\n/", "\\n", addslashes($elig));

?>
<td>
   <button onclick="open_win('<?= $aname ?>', '<?= $descr ?>')">Open</button>

</td>

<td>
   <button onclick="open_win('<?= $aname ?>', '<?= $elig ?>')">Open</button>
</td>

<?	
	print ("</tr>");
//exit;
} //while
//echo '<pre>all id'; var_export($search_id_list); echo '</pre>';
?>

 </table> 
  
<script>
function open_win(name, text) {
    window.open('youPopUpPage.php?text=' + encodeURIComponent(text) + '&name=' + name, '_blank','toolbar=0,location=no,menubar=0,height=600,width=800,left=200, top=300');
}
</script>
 </table>
<bR><div align="center"><img src="../images/linecalendarpopup500.jpg"></div><Br>

</body>
</html>

