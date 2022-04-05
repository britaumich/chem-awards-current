<?php
require_once('../access.php');
require_once('../library/HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();
if (admin_access()) {
?>
<div class="bodypad">
<div align="center"><br>
<div class="facrecbox1"><div class="textalignleft pad15and10">
<div align="center"><br><br><h1>Chemistry Awards - Admin Site<br></h1><br>
</div>
<div class="navbar">
<a class="navlink" href="allawards.php">All Awards</a>
<a class="navlink" href="allfaculty.php">All Faculty</a>
<a class="navlink" href="letter.php">Upload Documents</a>
<!--
<a class="navlink" href="check_award.php">Faculty View</a>
<a class="navlink" href="edit_eligibility.php">Eligibility List</a>
<a class="navlink" href="award_interested.php">Who is interested</a>
-->
<a class="navlink" href="add_nominations.php">Award Status Update</a>
</div>
<br>
<?php
} else {
   echo ("You are not authorized to run this action");
   exit;
}
?>
