<?php
require_once('../access.php');
require_once('../library/HTMLPurifier.auto.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

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
<a class="navlink" href="add_nominations.php">Award Status Update</a>
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
