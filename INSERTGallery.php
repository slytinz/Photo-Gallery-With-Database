<?php
//Connecting To Server
   $servername = "mariadb";
   $username = "cs431s40";
   $pw = "Poh7Ainu";
   $dbname = "cs431s40";

//  $servername = "localhost";
//  $username = "root";
//  $pw = "";
//  $dbname = "Gallery";

$conn = mysqli_connect($servername, $username, $pw, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

//////////

if(isset($_POST['submit'])){
  // create short variable names
  $photo= $_POST['photoname'];
  $date = $_POST['date'];
  $photographer = $_POST['photographer'];
  $location = $_POST['location'];
//  $pdir = 'uploads';




 // Uploaded image properties
 $file = $_FILES['upfile'];
 $fileName = $_FILES['upfile']['name'];
 $fileType = $file["type"];
 $fileTempName = $file["tmp_name"];
}

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

echo $imgPath;
 //now do the sql
 //already connected above...
 // $stm = mysqli_stmt_init($conn);
 //$insertSQL = 'INSERT INTO Images (titleGallery, dateGallery, locGallery, photographer, orderGallery, imageDir) VALUES (?,?,?,?,?,?);';
 //  if(!mysqli_stmt_prepare($stm, $insertSQL)){
 //   echo "SQL INSERT INTO FAILED";
 //    }

 $allSQL = "SELECT * FROM gallery;";
 $stm = mysqli_stmt_init($conn);


// if(!mysqli_stmt_prepare($stm, $allSQL)){
//   if($stm ->prepare('INSERT INTO Images(titleGallery, dateGallery, locGallery, photographer, orderGallery, imageDir) VALUES (?,?,?,?,?,?);')){
//	echo "SQL SELECT * FROM FAILED";
// }

// else{
//   mysqli_stmt_execute($stm);
//   $result = mysqli_stmt_get_result($stm);

   //FOR UPLOAD ORDER.
   $rowCount = mysqli_num_rows($result);
   $setImgOrder = $rowCount+1;

   //INSERTING INTO DATABASE
   $photo = mysqli_real_escape_string($conn, $photo);
   $date = mysqli_real_escape_string($conn, $date);
   $location = mysqli_real_escape_string($conn, $location);
   $photographer = mysqli_real_escape_string($conn, $photographer);
   $sqlPath = mysqli_real_escape_string($conn, $imgPath);
   $setImgOrder = mysqli_real_escape_string($conn, $setImgOrder);
   if(mysqli_multi_query($conn, "INSERT INTO Images (titleGallery, dateGallery, locGallery, photographer, imageDir) VALUES ('".$photo."','".$date."','".$location."','".$photographer."','".$imgPath."')")){

  // if(!mysqli_stmt_prepare($stm, $insertSQL)){
  //   echo "SQL INSERT INTO FAILED";
  // }
  //else{
  
	echo "INSERTED IN DB";
  }
  else{
	echo "ERROR: Could not execute INSERT";
  } 

 // mysqli_stmt_bind_param($stm, "ssssss", $photo, $date, $location, $photographer,$setImgOrder, $imgPath);
//  mysqli_stmt_execute($stm);


//DOESN'T UPLOAD....
 if (!move_uploaded_file($fileTempName, $imgPath))
  {
 echo "Problem: Could not move file to destination directory.";
 //exit;

 }//isset
//}
//}

 ?>


<!-- <!DOCTYPE html>
<html>
  <head>
    <title>Upload image- image uploaded</title>
  </head>
  <body>

    <h1>Image uploaded (probably)</h1>
    <p>Your image (shown  below) has been uploaded</p>

    <div>

    //  echo "<p>image name ".nl2br(htmlspecialchars($_FILES['upfile']['name']))." <p>";
     // echo "<img scr=/uploads/$_FILES['upfile']['name'] />"; //breaks
     //echo '<img scr="'. $dir. '/'.nl2br(htmlspecialchars($_FILES['upfile']['name'])).'">')
      // echo "<img scr="./uploads/' .$_FILES['upfile']['name']. '"/>"; //breaks?


    </div>

  </body>
</html> -->

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
    </div> <!-- End of the contrainer -->

    <br />

    <div class = "gallery-containter">
      <?php
      $orderQuery= "SELECT * FROM gallery ORDER BY orderGallery ASC";
      $stm = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stm, $orderQuery)){
        echo "SQL SORTING ORDER FAILED!";
      }
      else{
        $order= $_POST['order'];
        mysqli_stmt_execute($stm);
      }
        $conn->close();
      ?>
    </div>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
  </body>

</html>
