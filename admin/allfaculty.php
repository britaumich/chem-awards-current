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
require_once('nav.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
 
$rank = 'all';
$order = " ORDER BY Name";
$sqls = "SELECT faculty.`id` as id, `uniqname`, `Name`, faculty.`Rank`, rank.rank, `Appt_Start`, `Year_Tenured`, `Year_Promoted` FROM `faculty` JOIN rank ON faculty.Rank = rank.id ";
$sqlsearch = $sqls . $order;

if (isset($_POST['submit'])) {

     $rank = $purifier->purify($_REQUEST['Rank']);
     $where = ' where 1';
     if ($rank !== 'all' ) { $where .= " and faculty.Rank = '" . $rank . "'"; }

     $sqlsearch =  $sqls . $where . $order;
//echo $sqlsearch;
//exit;

}

echo "<div class='imgfloatright'><form name='form2' action='edit_faculty.php' method='post'>";
echo ('<input type="submit" name="add" value="Add a New Faculty Record">');
 echo('</form>');
echo "<form name='form5' action='deletefaculty.php' method='post'>";
echo ('<input type="submit" name="remove" value="Delete Faculty">');
 echo('</form></div>');
    echo "<div class='imgfloatleft'>";
    echo "<form name='form' method='post' action='allfaculty.php'>";

$sqlrank = "SELECT id, rank FROM rank";
$resultrank = mysqli_query($conn, $sqlrank) or die("There was an error 1: ".mysqli_error($conn));
echo "Rank: <select name='Rank'>";
        echo "<option select value='all'> - all -</option>";
if (mysqli_num_rows($resultrank) != 0) {
     while ( $ranks = mysqli_fetch_array($resultrank, MYSQLI_BOTH) ) {
           echo "<option";
           if ($ranks['id'] == $rank) { echo " selected"; }
           echo " value=$ranks[id]>$ranks[rank]</option>";
     }
     echo "</select><br>";
}
?>
      <br> <input type="submit" name="submit" value="Search">
        </form>
<?php 

 $result = mysqli_query($conn, $sqlsearch) or die("There was an error 2: ".mysqli_error($conn));
$total=mysqli_num_rows($result);
?>
<?php

echo "</div><Br><Br>";
 
 if( $total == 0 )
 {
    echo "<br><br>There were no results found. ><Br></Br>";
 }//if
 else  {
     echo "<br><br><Strong>Total:</Strong> ".$total;

 //show table headers for results
 
echo ("<table style='width:100%'>
<tr>
	<th></a>Unique Name<br>(Click to view)</th>
	<th>Name<br> (click to edit)</th>
	<th>Rank</a></th>
	<th>Appt Start</th>
	<th>Year Tenured</th>
	<th>Year Promoted</th>

</tr>
");
}//else

while ( $adata = mysqli_fetch_array($result, MYSQLI_BOTH) ) 
{
	
   $id = $adata['id'];
	echo ("<tr>");
		
//		echo "<td>$adata[id]</td>";
		echo"<td><a href='faculty.php?id=$adata[id]'>$adata[uniqname]</a></td>";
		echo"<td style='white-space:nowrap'><a href='edit_faculty.php?id=$adata[id]'>$adata[Name]</a></td>";
		echo "<td style='white-space:nowrap'>$adata[rank]</td>";
		echo "<td>$adata[Appt_Start]</td>";
		echo "<td>$adata[Year_Tenured]</td>";
		echo "<td>$adata[Year_Promoted]</td>";
}
?>

<script>
function open_win(name, text) {
    window.open('youPopUpPage.php?text=' + text + '&name=' + name, '_blank','toolbar=0,location=no,menubar=0,height=400,width=400,left=200, top=300');}
</script>
 </table> 
 <bR><div align="center"><img src="../images/linecalendarpopup500.jpg"></div><Br>
 
</body>
</html>
