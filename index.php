<?php
 //starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/database_queries.php");
//starting code
$firstName = $lastName = $displayName = $email = $letter = $username = "";
$logged_in_status = $friendsResult = null;
if (logged_in()) {
  $firstName = $_SESSION["firstname"];
  $lastName = $_SESSION["lastname"];
  $displayName = $_SESSION["displayname"];
  $email = $_SESSION["email"];
  $username = $_SESSION["username"];
  $letter = $firstName[0];
  $user = get_user($username)[0];
  $result = get_home($user, "isBlocked");
  $friendsResult = have_friends($user);
  $logged_in_status = 1;
} else {
    $result = get_home();
    $logged_in_status = 0;
}
?>

<?php require_once("basepagetop.php");?>
<div class="content">
    <div id="most-viewed">
        <div class="multimedia-content-container">
            <div class="multimedia-content">
                <?php
                //if you're logged in and have friends but there is no update from them
                if($logged_in_status && $friendsResult == 1 && (mysqli_num_rows($result) < 1)) {
                    echo '
                        <div class="friends-update-container">
                            <div class="friends-update">
                                <span>No updates from your friends yet.</span>
                            </div>
                        </div>
                    ';
                }
                //if logged in and do not have friends
                if($logged_in_status && $friendsResult == 0) {
                    echo '
                        <div class="friends-update-container">
                            <div class="friends-update">
                                <span>You do not have any friends to view their updates on your timeline, search for someone on meTube</span>
                                <a class="btn btn-link" href="' . BASE_PATH . 'people_search.php" role="button">Search</a></p>
                            </div>
                        </div>
                    ';
                }
                while($row = get_array($result)) {
                    $media_id = $uploadedBy = $title = $description = $type = $extension = $path = $view_count = $time = $date_diff = null;
                    $thumbnailSrc = "";
                    foreach ($row as $key => $value) {
                        if($key == 'media_id') $media_id = $value;
                        if($key == 'title') $title = $value;
                        if($key == 'media_description') $description = $value;
                        if($key == 'media_type') $type = $value;
                        if($key == 'extension') $extension = $value;
                        if($key == 'file_path') $path = $value;
                        if($key == 'view_count') $view_count = $value;
                        if($key == 'upload_time') $time = $value;
                        if($key == 'date_diff') $date_diff = $value;
                        if($key == 'uploader') $uploadedBy = $value;
                    }
                    //display with our placeholders
                    if ($type == "video") {
                        $thumbnailSrc .= BASE_PATH."placeholders/video-image.jpg";
                    } elseif ($type == "audio") {
                        $thumbnailSrc .= BASE_PATH."placeholders/audio-image.png";
                    } elseif ($type == "image") {
                        $thumbnailSrc .= "https://via.placeholder.com/240x138.png?text=Image";
                    } else {
                        $thumbnailSrc .= "https://via.placeholder.com/240x138.png?text=Gif";
                    }

                    $url = BASE_PATH."display_media.php?media_id=$media_id";
                    echo '
                    <div class="media-item-container">
                    <div class="media-item">
                        <div class="media-wrapper">
                            <a id="thumbnail" href='. $url .'>
                                <img src='. $thumbnailSrc .'>
                            </a>
                        </div>
                        <div class="text-wrapper">
                            <div class="media-rank">
                                <div class="media-title-wrapper">
                                    <h3 class="media-title">'. $title .'</h3>
                                </div>
                                <div class="metadata">
                                    <span id="media-metadata">'. $view_count .' views</span>
                                    <span class="time">'. $date_diff .' days ago</span>
                                </div>
                            </div>
                            <div class="media-description">
                                <p>'. $description .'</p>
                            </div>
                        </div>
                    </div>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php require_once("basepagebottom.php");?>


<?php
  /*if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/dashboard/');
	exit;*/
?>
