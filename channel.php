<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");
define("CHANNEL_PATH","");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/test_user_input.php");
require_once("php_scripts/database_queries.php");
require_once("php_scripts/table_values.php");

//starting code
if (!logged_in()) {
  header("Location:login.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$letter = $firstName[0];
$logged_in_flag = 1;

$channelid = -1;
$requestedMediaType = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (!empty($_GET["channelid"])) {
    $channelid = $_GET["channelid"];
  }
  if (!empty($_GET["type"])) {
    $requestedMediaType = $_GET["type"];
  }
}

$myID = get_user($email)[0];
if($channelid == -1 || $channelid == $myID){
  header("Location:mypage/channel.php");
}

settype($channelid,"integer");
$channelInfo = get_user($channelid);
if(empty($channelInfo)){
  header("Location:mypage/channel.php");
}

$channelDisplayName = $channelInfo[6];
$subscribeCount = get_row(get_number_of_subscribers($channelid))[0];

$mediaResult = $result = $users_favorite = null;
$mediaType = "Uploads";
switch($requestedMediaType){
  case 'images':
    $mediaResult = get_all_media_images($channelid);
    $mediaType = "Images";
    break;
  case 'video':
    $mediaResult = get_all_media_videos($channelid);
    $mediaType = "Videos";
    break;
  case 'anime':
    $mediaResult = get_all_media_anime($channelid);
    $mediaType = "Anime";
    break;
  case 'audio':
    $mediaResult = get_all_media_audio($channelid);
    $mediaType = "Audio";
    break;
  case 'playlist':
    $result = all_playlist($channelid);
    $users_favorite = get_favorite($channelid);
    $mediaType = "playlist";
    break;
  case 'favorite':
    $result = all_favorite($channelid);
    $users_favorite = get_favorite($channelid);
    $mediaType = "favorite";
    break;
  default:
    $mediaResult = get_all_media($channelid);
    break;
}
$suffix1 = "?channelid=".$channelid;
$suffix2 = "&channelid=".$channelid;
?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <?php require_once("channeltop.php")?>
  <?php
    if (($mediaType == "Images") || ($mediaType == "Videos") || ($mediaType == "Anime") || ($mediaType == "Audio") || ($mediaType == "Uploads")) {
      require_once("channelmediadisplay.php");
    } else if($mediaType == "playlist") {
      require_once("channelplaylist.php");
    } else if ($mediaType == "favorite") {
      require_once("channelfavorite.php");
    }
  ?>
</div>
<?php require_once("channelbottom.php")?>
<?php require_once("basepagebottom.php"); ?>
