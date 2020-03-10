<?php

//Connecting To Server
  $servername = "mariadb";
  $username = "cs431s40";
  $pw = "Poh7Ainu";
  $dbname = "cs431s40";

$conn = mysqli_connect($servername, $username, $pw, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//////////

if(isset($_POST['submit'])){
  // create short variable names
  $photoname = $_POST['photoname'];
  $photodate = $_POST['photodate'];
  $photographer = $_POST['photographer'];
  $phlocation = $_POST['phlocation'];
//  $pdir = 'uploads';

 }

 // Uploaded image properties
 $file = $_FILES['upfile']['file'];
 $fileName = $file['upfile']["name"];
 $fileType = $file['upfile']["type"];
 $fileTempName = $file['upfile']["tmp_name"];
// $fileError = $file['upfile']["error"];
// $fileSize = $file['upfile']["size"]
// $fileSizeMB = ($fileSize / 1024 / 1024);

if ($_FILES['fileUp']['error'] > 0)
{
echo 'Problem: ';
switch ($_FILES['fileUp']['error'])
{
 case 1:
  echo 'File exceeded upload_max_filesize.';
  break;
 case 2:
  echo 'File exceeded max_file_size.';
  break;
 case 3:
  echo 'File only partially uploaded.';
  break;
 case 4:
  echo 'No file uploaded.';
  break;
 case 6:
  echo 'Cannot upload file: No temp directory specified.';
  break;
 case 7:
  echo 'Upload failed: Cannot write to disk.';
  break;
 case 8:
  echo 'A PHP extension blocked the file upload.';
  break;
 }
 exit;
}

 //Extension Grabber
 $path = $_FILES['upfile']['name'];
 $fileExt = pathinfo($path, PATHINFO_EXTENSION);//Seperating and grabbing only the extension from get_included_files

 //What type of files are allowed to be uploaded in this application
 $allow = array("jpg", "JPEG", "jpeg", "png");
 if(!in_array($fileExt, $allow)){
  echo "INCORRECT FILE TYPE!";
  exit();
 }

//now do the sql
//already connected above...
$insertSQL = "INSERT INTO Images (titleGallery, dateGallery, locGallery, photographer, orderGallery, imageDir) VALUES (?,?,?,?,?,?);";
 if(!mysqli_stmt_prepare($stm, $insertSQL)){
  echo "SQL INSERT INTO FAILED";
   }
 else{
  mysqli_stmt_bind_param($stm, "ssssss", $photoname, $photodate, $phlocation, $photographer,$setImgOrder, $path);
  mysqli_stmt_execute($stm);

if (!move_uploaded_file($_FILES['upfile']['tmp_name'], "uploads/".$_FILES['upfile']['name']))
 {
echo 'Problem: Could not move file to destination directory.';
//exit;

}//isset

$conn->close();

?>


<!DOCTYPE html>
<html>
  <head>
    <title>Upload image- image uploaded</title>
  </head>
  <body>

    <h1>Image uploaded (probably)</h1>
    <p>Your image (shown  below) has been uploaded</p>

    <?php
    echo "<p>image name ".nl2br(htmlspecialchars($_FILES['upfile']['name']))."<p>";
   // echo "<img scr=/uploads/$_FILES['upfile']['name'] />"; //breaks
   //echo '<img scr="'. $dir. '/'.nl2br(htmlspecialchars($_FILES['upfile']['name'])).'">')
    echo '<img scr="uploads/' .$_FILES['upfile']['name']. '"/>'; //breaks?

  ?>
  </body>
</html>