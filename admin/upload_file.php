<?php      
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
<title>Chemistry Award  - University of Michigan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META content="" name=KEYWORDS>
<META content="" name=description>
<link rel="stylesheet" href="../eebstyle.css">
<link rel="shortcut icon" href="favicon.ico">
</head>
<body>
<?php  
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
require_once('nav.php');
require_once "../php_mail.inc";
$again = '';
$error = '';
// if the recomtext field is empty 
if(isset($_POST['recomtext']) && $_REQUEST['recomtext'] != ""){
// let the spammer think that they got their message through
$recomtext = $purifier->purify($_REQUEST['recomtext']);
echo $recomtext;
   echo "<h1>Thanks</h1>";
exit;
}
//
//$recemailnew = "";
if(isset($_POST['submit'])) {

$filename = "test_file_name.pdf";
$uploadfile = $uploaddir . $filename;
$tmpfile = $_FILES['recfilename']['tmp_name'];

// Create a cURL handle
$ch = curl_init('https://apps.chem.lsa.umich.edu/chem-awards/admin/upload_file.php');

// Create a CURLStringFile object
//$cstringfile = new CURLStringFile($tmpfile, $uploadfile, $_FILES['recfilename']['type']);
//$cstringfile = new \CURLFile($tmpfile, $_FILES['recfilename']['type'], $uploadfile);
$txt = 'test content';
$txt_file = 'data://application/octet-stream;base64,' . base64_encode($txt);
//$txt_curlfile = new \CURLFile($txt_file, 'text/plain', $uploadfile);
$txt_curlfile = new \CURLStringFile($txt, 'text/plain', $uploadfile);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $txt_curlfile]);
/*
curl_exec($ch);

// Assign POST data
$data = array('file' => $cstringfile);
var_export($data);
curl_setopt($ch, CURLOPT_VERBOSE, true);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));
//curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
*/

// Execute the handle
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);
var_export($httpCode);
if ($response === false){ 
    $response = curl_error($ch);
    echo stripslashes($response);
    exit;
} else {
    echo("HELL!!!!");
   exit;
}

curl_close($ch);
}

?>
<div align="center"><h2>Upload a CV <br><br><h2>
</div></h2>
<form method="post" action="upload_file.php" enctype="multipart/form-data">
<strong>Select a Faculy: </strong> 
 <?php
//$lettertype = $purifier->purify($_REQUEST['lettertype']);
//$lettertype1 = $purifier->purify($_REQUEST['lettertype1']);
$lettertype = "cv";
$lettertype1 = "cv";
if ($again == "yes") {
    $uniqname = "";
    $lettertype = "cv";
    $lettertype1 = "cv";
}
?>
<!--
<div id="txtHint0"></div>
<div id="txtHint"></div>
<div id="txtHint1"></div>
-->
<br><br>
<img src="../images/box650top.jpg"><div class="box650mid"><div class="pad15and10">
<h3>Upload File</h3>
<Br>
Must be <strong>ONE file</strong> and be in <strong>PDF or DOC format</strong>. Maximum file size is 20 MB.
<br><br>
<b>File:</b> <input type="file" name="recfilename"><br>
</div></div>
<img src="../images/box650btm.jpg">

<br>
<br>
<input type="checkbox" name="replacefile" value="yes"> Check to replace the file<br><br>
<input type="submit" name="submit" value="Submit Form" />

<br>
<br>
<bR><div align="center"><img src="../images/linecalendarpopup500.jpg"></div>
</form>
</body> 
</html>
