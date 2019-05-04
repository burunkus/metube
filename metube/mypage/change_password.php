<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");

//required scripts
require_once("../php_scripts/helper_functions.php");
require_once("../php_scripts/test_user_input.php");

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
$cPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $valid = true;
  if (!empty($_POST["cancel"])) {
    header("Location:settings.php");
  }
  if (!test_current_password()) {
    $valid = false;
  }
  if (!test_passwords()) {
    $valid = false;
  }
  if ($valid) {
    change_password($username, $password, $email);
    $_SESSION["password"] = $password;
    header("Location:settings.php");
  }
}
?>

<?php require_once("../basepagetop.php"); ?>
<div class="content">
  <!--PAGE DYNAMIC CONTENT-->
  <div class="settings-container">
    <div class="settings">
      <div class="container">
        <!--boostrap-->
        <form id="changePassword" class="settings-changes-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <h1>Change Password</h1>
          <div class="form-group col-md-7">
            <label for="cpassword">Current Password</label>
            <input type="password" class="form-control settings-test-input" id="cpassword" name="cpassword" value="<?php echo $cPassword; ?>">
            <?php display_error($cPasswordErr) ?>
          </div>
          <div class="form-group col-md-7">
            <label>New password</label>
            <input type="password" class="form-control settings-test-input" id="password" name="password" value="<?php echo $password; ?>">
            <?php display_error($passwordErr) ?>
          </div>
          <div class="form-group col-md-7">
            <label>Confirm new password</label>
            <input type="password" class="form-control settings-test-input" id="repassword" name="repassword" value="<?php echo $passwordVerify; ?>">
            <?php display_error($passVerifyErr) ?>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="submit" id="cancel" name="cancel" class="btn btn-secondary" value="cancel">Cancel</button>
        </form>
      </div>
    </div>
  </div>
  <!--END OF PAGE DYNAMIC CONTENT-->
</div>
<?php require_once("../basepagebottom.php"); ?>
