<?php
//Connecting To Server
   $servername = "";
   $username = "";
   $pw = "";
   $dbname = "";

$conn = mysqli_connect($servername, $username, $pw, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if(isset($_POST['submit'])){
  // create short variable names
  $photo= $_POST['photoname'];
  $date = $_POST['photodate'];
  $photographer = $_POST['photographer'];
  $location = $_POST['phlocation'];


 // Uploaded image properties
 $file = $_FILES['upfile'];
 $fileName = $_FILES['upfile']['name'];
 $fileType = $file["type"];
 $fileTempName = $file["tmp_name"];

 if ($_FILES['upfile']['error'] > 0)
 {
 echo "Problem: ";
 switch ($_FILES['fileUp']['error'])
 {
  case 1:
   echo "File exceeded upload_max_filesize.";
   break;
  case 2:
   echo "File exceeded max_file_size.";
   break;
  case 3:
   echo "File only partially uploaded.";
   break;
  case 4:
   echo "No file uploaded.";
   break;
  case 6:
   echo "Cannot upload file: No temp directory specified.";
   break;
  case 7:
   echo "Upload failed: Cannot write to disk.";
   break;
  case 8:
   echo "A PHP extension blocked the file upload.";
   break;
  }
  exit;
 }



 //Extension Grabber
 $path = $_FILES['upfile']['name'];
 $fileExt = pathinfo($path, PATHINFO_EXTENSION);//Seperating and grabbing only the extension from get_included_files
 $imgPath = "./uploads/" . $path;
 //now do the sql
 //already connected above...


 //FOR UPLOAD ORDER.
$rowCount = mysqli_num_rows($result);
$setImgOrder = $rowCount+1;


 $sql = "INSERT INTO Images (titleGallery, dateGallery, locGallery, photographer, orderGallery, imageDir) VALUES ('".$photo."','".$date."','".$location."','".$photographer."', '".$setImgOrder."','".$imgPath."')";
 $stm = mysqli_stmt_init($conn);

   //INSERTING INTO DATABASE
 if(mysqli_multi_query($conn, $sql)){
     echo " INSERTED IN DB";
 }
 else{
     echo "ERROR: Could not execute INSERT";
 }

//DOESN'T UPLOAD....
 if (!move_uploaded_file($fileTempName, $imgPath))
  {
 echo "Problem: Could not move file to destination directory.";
 //exit;
  }

 }//isset

   $conn->close();

 ?>


<!DOCTYPE html>
<html>
<head>
<style>

.gallery-container a div{
  width:100%;
  height: 235px;
  background-color: red;
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
}

.imggallery {
  background-image: url(<?php $fileDest ?>);
  background-color: #cccccc;
  border-style: solid;
  margin-left: auto;
  margin-right: auto;
  height: 200px;
  width:300px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
  position: relative;
  padding: 1px;
}

.gallery {
  border: 0px solid #ccc;
}

.gallery:hover {
  border: 0.5px solid #77;
}


.desc {
  padding: 10px;
  font-size: 15px;
  text-align: left;
}

.responsive {
  padding: 0 6px;
  float: left;
  width: 25%;
}




</style>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Gallery</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
</head>

<body style="background-color: white">


<div class="container">
  <div class="page-header">
    <br />
    <h1>View All Photos</h1>
    <hr class="my-4">
  </div>


  <div class="row">
    <!-- A button to upload more photos into the gallery -->
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <a class="btn btn-primary" href="Index.html" role="button">Upload</a>
    </div>


    <!-- The sorting dropdown mechanism for photos to be sorted in a certain way -->
     <div>
      <form method="POST">
        <label for= "order"><b>Sort By:</b></label>
          <select name="order" id="order" >
            <option value = "PhotoName" selected>Photo Name</option>
            <option value = "Date">Date</option>
            <option value = "Location">Location</option>
            <option value = "Photographer">Photographer</option>
          </select>
          <input type="submit" name="SUBMIT" />
      </form>
    </div>
  </div>

<br />

<?php
//Connecting To Server
   $servername = "mariadb";
   $username = "cs431s40";
   $pw = "Poh7Ainu";
   $dbname = "cs431s40";


$conn = mysqli_connect($servername, $username, $pw, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
  // echo "Enter else. Connection confirmed!";
  if(isset($_POST)){
    $order = $_POST['order'];
  }
  switch($order){
    case "PhotoName":
      $sql = "SELECT * FROM Images ORDER BY titleGallery ASC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class = "responsive">
                  <div class="gallery">
                    <img src=' . $row['imageDir'] . ' height=200px width=200px><br />
                    <b>Name:</b>' . $row["titleGallery"].'<br />
                    <b>Taken by </b> '.$row["photographer"].'<br />
                    <b>Location: </b>'.$row["locGallery"].'<br />
                    <b>Date: </b>' . $row["dateGallery"]. '<br />
                  </div>
                </div>';
        }
       }
       break;
    case "Date":
      $sql = "SELECT * FROM Images ORDER BY dateGallery ASC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class = "responsive">
                  <div class="gallery">
                    <img src=' . $row['imageDir'] . ' height=200px width=200px><br />
                    <b>Name:</b>' . $row["titleGallery"].'<br />
                    <b>Taken by </b> '.$row["photographer"].'<br />
                    <b>Location: </b>'.$row["locGallery"].'<br />
                    <b>Date: </b>' . $row["dateGallery"]. '<br />
                  </div>
                </div>';
          }
        }
        break;
    case "Location":
      $sql = "SELECT * FROM Images ORDER BY locGallery ASC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class = "responsive">
                  <div class="gallery">
                    <img src=' . $row['imageDir'] . ' height=200px width=200px><br />
                    <b>Name:</b>' . $row["titleGallery"].'<br />
                    <b>Taken by </b> '.$row["photographer"].'<br />
                    <b>Location: </b>'.$row["locGallery"].'<br />
                    <b>Date: </b>' . $row["dateGallery"]. '<br />
                  </div>
                </div>';
          }
      }
      break;
    case "Photographer":
      $sql = "SELECT * FROM Images ORDER BY photographer ASC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class = "responsive">
                  <div class="gallery">
                    <img src=' . $row['imageDir'] . ' height=200px width=200px><br />
                    <b>Name:</b>' . $row["titleGallery"].'<br />
                    <b>Taken by </b> '.$row["photographer"].'<br />
                    <b>Location: </b>'.$row["locGallery"].'<br />
                    <b>Date: </b>' . $row["dateGallery"]. '<br />
                  </div>
                </div>';
          }
      }
      break;
    default:
      $sql = "SELECT * FROM Images";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class = "responsive">
                  <div class="gallery">
                    <img src=' . $row['imageDir'] . ' height=200px width=200px><br />
                    <b>Name:</b>' . $row["titleGallery"].'<br />
                    <b>Taken by </b> '.$row["photographer"].'<br />
                    <b>Location: </b>'.$row["locGallery"].'<br />
                    <b>Date: </b>' . $row["dateGallery"]. '<br />
                  </div>
                </div>';
        }
      }
  }
}

$conn->close();
?>
<!-- End of the container -->
</div>





<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
</body>

</html>
