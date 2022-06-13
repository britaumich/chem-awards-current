<?php
session_start();
require_once('../library/HTMLPurifier.auto.php');
require_once('access.php');
$purifier = new HTMLPurifier();
if (non_admin_access()) {
?>
<div class="bodypad">

<div align="center"><br>
<div class="facrecbox1"><div class="textalignleft pad15and10">
<div align="center"><br><br><h1>Chemistry Awards - Faculty Site<br></h1><br>
</div>
<div class="navbar">
<a class="navlink" href="faculty.php">My Information</a>
<a class="navlink" href="letter.php">Upload a CV</a>
<a class="navlink" href="allawards.php">All Awards</a>
</div>
<div style="color:blue;text-align:center">
The application is moved to a new server. If you have bookmarks please update them.
</div>
<br>
<?php
} else {
   forceRedirect('https://apps.chem.lsa.umich.edu/chem-awards/no_access.php');
}
?>
