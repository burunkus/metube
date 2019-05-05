<?php
//session_start();
//require_once("helper_functions.php");

$media_type = $media_format = $media_description = $media_category = $media_title = $media_keywords = $media_access = $media_discussion_type = $rate_media = "";
$kwArray = array();
$keywords = null;
$keyword = null;
$conn = get_conn();

$media_type_error = $media_format_error = $media_description_error = $keyword_error = $media_title_error = $result = "";
$success = null;

function process_upload() {
  global $media_type_error, $media_format_error, $media_description_error, $keyword_error, $media_title_error, $result, $username, $conn, $kwArray, $keywords, $keyword, $success;

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $media_type =  $_POST["mediaType"];
    if(empty($media_type)) {
      $media_type_error = "Media type can't be empty";
      //echo $media_type_error;
      return;
    }
    $media_format = $_POST["mediaFormat"];
    if(empty($media_format)) {
      $media_format_error = "Media format can't be empty";
      return;
    }
    $media_description = mysqli_real_escape_string($conn, $_POST["mediaDescription"]);
    if(empty($media_description)) {
      $media_description_error = "Media description can't be empty";
      return;
    }
    $media_category = $_POST["mediaCategory"];  //array
    $media_keywords = mysqli_real_escape_string($conn, $_POST["mediaKeywords"]);
    if(!empty($media_keywords)) {
        $keywords = explode(',', $media_keywords);
        if(count($keywords) > 1) {
          for($i = 0; $i < count($keywords); $i++) {
            if(empty($keywords[$i])) continue;
            $keyword = trim($keywords[$i]);
            if(!test_uploader_keyword($keyword)) {
              $keyword_error = "Make sure you have not entered any special characters or numbers";
              return;
            } else {
              $kwArray[$i] = $keyword;
            }
          }
        } else {  // only one keyword entered
          $media_keywords = trim($media_keywords);
          if(!test_uploader_keyword($media_keywords)) {
            $keyword_error = "Make sure you have not entered any special characters or number";
            return;
          } else {
            $kwArray[0] = $media_keywords;
          }
        }
    } else {  //empty keyword
      $keyword_error = "Keywords cannot be empty";
      return;
    }
    $media_title = mysqli_real_escape_string($conn, $_POST["mediaTitle"]);
    if(empty($media_title)) {
      $media_title_error = "media title can't be empty";
      return;
    }
    $media_access = $_POST["mediaAccess"];
    $media_discussion_type = $_POST["mediaDiscussionType"];
    $rate_media = $_POST["mediaRate"];

    if(isset($_POST["deny"])) {
      $usersToBlock = $_POST["deny"];
      $usersToBlock = json_decode($usersToBlock);
    } else {
      $usersToBlock = array();
    }


    //Create Directory if doesn't exist
    if(!file_exists('media/')) {
      mkdir('media/', 0744);
      chmod('media/', 0755);
    }
    $dirfile = 'media/'.get_user($username)[0].'/';
    if(!file_exists($dirfile)) {
      mkdir($dirfile, 0744);
      chmod($dirfile, 0755);
    }

    if(isset($_FILES['file'])) {
      if($_FILES["file"]["error"] > 0) {
        $result = upload_error($_FILES["file"]["error"]);
        return;
      }
      else {
        $upfile = $dirfile.urlencode($_FILES["file"]["name"]);
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES["file"]["tmp_name"];
        $file_size =$_FILES['file']['size'];
        $file_type =$_FILES['file']['type'];
        $ext = explode('.', $file_name);
        $ext = strtolower($ext[count($ext) - 1]);
        $extensions = array("jpeg","jpg","png", "mp4", "mp3", "gif");

        //check if file is what we support
        if($ext != $media_format) {
          $result = "Extension not allowed, must be either jpeg, jpg, png, mp4, mp3 or gif";
          return;
        }

        //re-check file size
        if($file_size > 31457280) {
          $result = "File must be less than 30MB";
          return;
        }

        if(file_exists($upfile)) {
          $result = "File has been uploaded already";
          return;
        } else {
          if(is_uploaded_file($_FILES['file']['tmp_name'])) {
            if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile)) {
              $result= upload_error($_FILES["file"]["error"]);
              return;
            }
            else { //Successfully upload file
              chmod($upfile, 0644);  //set permission
              $uploader_id = get_user($username)[0];
              $file_name = urlencode($file_name);
              $file_path = $dirfile.$file_name;
              $inserted_media_id = upload_media($media_type, $media_format, $media_title, $media_description, $media_access, $media_discussion_type, $rate_media, $file_size, $file_path, $uploader_id);

              insert_other_templates($inserted_media_id, $media_category, $kwArray, $usersToBlock);
              //header("Location: ../upload.php");
              //let user know that their file has been uploaded
              //echo "uploaded Successfully";
              $success = 1;
              return;
            }
          }
          else {
            $result = upload_error($_FILES["file"]["error"]);
            return;
          }
        }
      }
    } else {
      $result = "file can't be empty";
      return;
    }
  }
}
?>
