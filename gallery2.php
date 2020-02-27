<?php
if(isset($_POST['submit'])){
  // create short variable names
  $photo = $_POST['photoname'];
  $date = $_POST['date'];
  $photographer = $_POST['photographer'];
  $location = $_POST['location'];

  // $document_root = $_SERVER["DOCUMENT_ROOT"];
  // $currentDir= getcwd();
  // $uploadDirectory= "/uploads/";
}
  $servername = "localhost";
  $username = "root";
  $pw = "";
  $dbname = "Gallery";

  $conn = mysqli_connect($servername, $username, $pw, $dbname);


         // Uploaded image properties
         if(isset($_POST['submit'])){
         $file = $_FILES['file'];
         $fileName = $file["name"];
         $fileType = $file["type"];
         $fileTempName = $file["tmp_name"];
         $fileError = $file["error"];
         $fileSize = $file["size"];

      //   if(isset($_POST['submit'])){
           //Extension Grabber
           $path = $_FILES['file']['name'];
           $fileExt = pathinfo($path, PATHINFO_EXTENSION);//Seperating and grabbing only the extension from get_included_files

           //What type of files are allowed to be uploaded in this application
           $allow = array("jpg", "JPEG", "jpeg", "png");

           if(in_array($fileExt, $allow)){
             if($fileError == 0){
               if($fileSize < 5000000){
                 $imgName = $path;
                 $fileDest = "./img/gallery/" . $imgName;

                 $allSQL = "SELECT * FROM gallery;";
                 $stm = mysqli_stmt_init($conn);

                 if(!mysqli_stmt_prepare($stm, $allSQL)){
                   echo "SQL1 FAILED";
                 }
                 else{
                   mysqli_stmt_execute($stm);
                   $result = mysqli_stmt_get_result($stm);
                   $rowCount = mysqli_num_rows($result);
                   $setImgOrder = $rowCount+1;

                   $insertSQL = "INSERT INTO gallery (titleGallery, dateGallery, locGallery, photographer, orderGallery, imageDir) VALUES (?,?,?,?,?,?);";
                   if(!mysqli_stmt_prepare($stm, $insertSQL)){
                     echo "SQL2 FAILED";
                   }
                   else{
                     mysqli_stmt_bind_param($stm, "ssssss", $photo, $date, $location, $photographer, $setImgOrder, $fileDest);
                     mysqli_stmt_execute($stm);

                     move_uploaded_file($fileTempName, $fileDest);
                   }
                 }
               }
               else{
                 echo "File size is too BIG!";
               }
             }
             else{
               echo "ERROR!! Unable to uploade photo!";
             }
           }
           else{
             echo "INCORRECT FILE TYPE!";
             exit();
           }

         }

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
    </div> <!-- End of the contrainer -->

    <br />

    <div class="gallery-containter">
        <!-- // Create SQL statements that corresponds with the Photo Name, Date, Location, Photographer
        // SQL statements that grab the values from the database in each column and print it fann_descale_output
        // Places order by SQL statements in the switch cases. Changes the order of the Photos
        // SQL Statements that print out objects in database including images
        //SADNESS AND THEN WE HAVE TO FORMAT. SON OF A BITCH -->
      <?php
      $orderQuery= "SELECT * FROM gallery ORDER BY orderGallery ASC";
    if($stm = $conn->prepare($orderQuery)){
      $stm = $conn->prepare($orderQuery);
      if(!mysqli_stmt_prepare($stm, $orderQuery)){
        echo "SQL3 failed";
      }
      else{
          $order= $_POST['order'];
          mysqli_stmt_execute($stm);
          $rez = mysqli_stmt_get_result($stm);

        //SWITCH FUNCTION for sorting
          switch($order){
            case "PhotoName":
              $orderQuery= "SELECT * FROM gallery ORDER BY titleGallery ASC";

              $result = $conn->query($orderQuery);
              while($row = mysqli_fetch_assoc($result)){
                echo '<div class = "responsive">
                      <div class = "gallery">
                        <div class = "imggallery" style="background-image: url('.$row["imageDir"].');"></div>
                        <div class = "desc">
                          <b>'.$row["titleGallery"].'</b><br />
                          Taken by: ' .$row["photographer"].'<br />
                          Location: ' .$row["locGallery"].'<br />
                          ' .$row["dateGallery"].'<br />
                          <div style="padding:6px;"></div>
                        </div>
                      </div>
                    </div>';
            }
              break;

            case "Date":
              $orderQuery= "SELECT * FROM gallery ORDER BY dateGallery ASC";

              $result = $conn->query($orderQuery);
                while($row = mysqli_fetch_assoc($result)){
                  echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class = "imggallery" style="background-image: url('.$row["imageDir"].');"></div>
                          <div class = "desc">
                            <b>'.$row["titleGallery"].'</b><br />
                            Taken by: ' .$row["photographer"].'<br />
                            Location: ' .$row["locGallery"].'<br />
                            ' .$row["dateGallery"].'<br />
                            <div style="padding:6px;"></div>
                          </div>
                        </div>
                      </div>';
              }

              break;


            case "Location":
              $orderQuery="SELECT * FROM gallery ORDER BY locGallery ASC";

              $result = $conn->query($orderQuery);
                while($row = mysqli_fetch_assoc($result)){
                  echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class = "imggallery" style="background-image: url('.$row["imageDir"].');"></div>
                          <div class = "desc">
                            <b>'.$row["titleGallery"].'</b><br />
                            Taken by: ' .$row["photographer"].'<br />
                            Location: ' .$row["locGallery"].'<br />
                            ' .$row["dateGallery"].'<br />
                            <div style="padding:6px;"></div>
                          </div>
                        </div>
                      </div>';
              }
              break;

            case "Photographer":
              $orderQuery="SELECT * FROM gallery ORDER BY photographer ASC";

              $result = $conn->query($orderQuery);
                while($row = mysqli_fetch_assoc($result)){
                  echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class = "imggallery" style="background-image: url('.$row["imageDir"].');"></div>
                          <div class = "desc">
                            <b>'.$row["titleGallery"].'</b><br />
                            Taken by: ' .$row["photographer"].'<br />
                            Location: ' .$row["locGallery"].'<br />
                            ' .$row["dateGallery"].'<br />
                            <div style="padding:6px;"></div>
                          </div>
                        </div>
                      </div>';
              }
              break;

            default:
              $orderQuery= "SELECT * FROM gallery ORDER BY titleGallery ASC";

              $result = $conn->query($orderQuery);
              while($row = mysqli_fetch_assoc($result)){
                echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class = "imggallery" style="background-image: url('.$row["imageDir"].');"></div>
                          <div class = "desc">
                          <b>'.$row["titleGallery"].'</b><br />
                          Taken by: ' .$row["photographer"].'<br />
                          Location: ' .$row["locGallery"].'<br />
                          ' .$row["dateGallery"].'<br />
                          <div style="padding:6px;"></div>
                        </div>
                      </div>
                    </div>';
            }
          }
        }
      

        $conn->close();
      }

         ?>
       </div> <!--The end of the gallery-container -->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
  </body>

</html>
