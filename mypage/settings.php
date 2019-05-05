<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");

//required scripts
require_once("../php_scripts/helper_functions.php");

//starting code
if (!logged_in()) {
  header("Location:../index.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$letter = $firstName[0];
?>

<?php require_once("../basepagetop.php"); ?>
<div class="content">
  <div class="settings-container">
    <div class="settings">
      <div class="account-setting">
        <div class="setting-title">
          <h3>Account</h3>
        </div>
        <div class="channel-section">
          <div class="channel-section-title">
            <h4>Your MeTube Channel</h4>
          </div>
          <div class="channel-section-details">
            <div class="channel-section-subtitle">
              <p>Your channel</p>
            </div>
            <div class="your-channel">
              <div class="channel-block-name">
                <!--echo initial-->
                <a href="/channel.html"></a>
                <p><?php echo $letter; ?></p>
              </div>
              <div class="account-setting-channel-info">
                <!--echo these things-->
                <a href="/channel.html" class="account-setting-channel-name"><?php echo $username; ?></a>
                <p class="account-setting-channel-email"><?php echo $email; ?></p>
              </div>
            </div>
          </div>
          <div class="settings-create-new-channel">
            <a href="<?php echo BASE_PATH . "mypage/channel.php"?>">Go To Channel</a>
          </div>
        </div>
      </div>
      <div class="update-settings">
        <div class="setting-title">
          <h3>Settings</h3>
        </div>
        <div class="change-container">
          <ul class="change">
            <li>
              <a href="change_name.php">Change name</a>
            </li>
            <li>
              <a href="change_email.php">Change email</a>
            </li>
            <li>
              <a href="change_username.php">Change username</a>
            </li>
            <li>
              <a href="change_password.php">Change password</a>
            </li>
            <li>
              <a href="change_display_name.php">Change display name</a>
            </li>
            <li>
              <a href="../blocked_users.php">View blocked users</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once("../basepagebottom.php"); ?>
