<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/test_user_input.php");
require_once("php_scripts/database_queries.php");

//starting code
if (!logged_in()) {
  header("Location:login.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$userid = get_user($email)[0];
$letter = $firstName[0];

$blocked_users = get_blocked_users($userid);
?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <!--PAGE DYNAMIC CONTENT-->
  <div class="blocked-users-wrapper">
    <div class="blocked-users">
      <div class="blocked-users-header">
        <div class="blocked-user-item">
          <span class="blocked-user-title">Blocked Users</span>
        </div>
      </div>
      <div class="blocked-users-container">
        <div class="blocked-user">
          <?php
          while($row = get_array($blocked_users)) {
            $first_name = $last_name = $media_title = $media_id = null;
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $media_title = $row['title'];
            $media_id = $row['media_id'];
            $user_id = $row['user_id'];
            echo '
            <div class="person-card-result">
              <div class="friend-container">
                <div class="friend-image-container">
                  <div class="friend-image">
                    <i class="fas fa-user"></i>
                  </div>
                </div>
                <div class="friend-name">
                  <p>'. $first_name .' '. $last_name .'</p>
                </div>
                <div class="media-name">
                  <p>'. $media_title .'</p>
                </div>
              </div>
              <input type="hidden" value="'. $user_id .'">
              <input type="hidden" value="'. $media_id .'">
              <button class="btn btn-link user" type="button">Unblock
              </button>
            </div>
            ';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once("basepagebottom.php"); ?>

