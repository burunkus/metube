<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");
define("CHANNEL_PATH","mypage/");

//required scripts
require_once("../php_scripts/helper_functions.php");
require_once("../php_scripts/test_user_input.php");
require_once("../php_scripts/database_queries.php");
require_once("../php_scripts/table_values.php");

//starting code
if (!logged_in()) {
  header("Location:../login.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$letter = $firstName[0];
$myID = get_user($email)[0];
$logged_in_flag = 1;
$subscribeCount = get_row(get_number_of_subscribers($myID))[0];
$requestedMediaType = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (!empty($_GET["type"])) {
    $requestedMediaType = $_GET["type"];
  }
}

$mediaResult = $result = $users_favorite = null;
$channelid = $myID;
$mediaType = "Uploads";
switch($requestedMediaType){
  case 'images':
    $mediaResult = get_all_media_images($myID);
    $mediaType = "Images";
    break;
  case 'video':
    $mediaResult = get_all_media_videos($myID);
    $mediaType = "Videos";
    break;
  case 'anime':
    $mediaResult = get_all_media_anime($myID);
    $mediaType = "Anime";
    break;
  case 'audio':
    $mediaResult = get_all_media_audio($myID);
    $mediaType = "Audio";
    break;
  case 'playlist':
    $result = all_playlist($myID);
    $users_favorite = get_favorite($myID);
    $mediaType = "playlist";
    break;
  case 'favorite':
    $result = all_favorite($myID);
    $users_favorite = get_favorite($myID);
    $mediaType = "favorite";
    break;
  default:
    $mediaResult = get_all_media($myID);
    break;
}
$channelDisplayName = $displayName;
$suffix1 = "";
$suffix2 = "";
?>

<?php require_once("../basepagetop.php"); ?>
<div class="content">
  <?php require_once("../channeltop.php")?>
  <?php
  if($mediaType == "playlist") {
    require_once("../channelplaylist.php");
  } else if ($mediaType == "favorite") {
    require_once("../channelfavorite.php");
  } else {
   require_once("../channelmediadisplay.php");
  }
  ?>
</div>
<?php require_once("../channelbottom.php")?>
<?php require_once("../basepagebottom.php"); ?>
