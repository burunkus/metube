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
  if (!test_new_email()) {
    $valid = false;
  }
  if ($valid) {
    if ($emailNew != $_SESSION["email"]) {
      change_email($email, $emailNew);
      $_SESSION["email"] = $emailNew;
    }
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
        <form id="updateDisplayName" class="settings-changes-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <h1>Change Email</h1>
          <div class="form-heading">
            <label class="form-label">Current Email</label>
            <div class="form-readonly-value"><?php echo $email; ?></div>
          </div>
          <div class="form-group col-md-7">
            <label for="emailnew">New Email</label>
            <input type="email" class="form-control settings-test-input" id="emailnew" name="emailnew" value="<?php echo $emailNew;?>">
            <?php display_error($emailNewErr) ?>
          </div>
          <div class="form-group col-md-7">
            <label>Current Password</label>
            <input type="password" class="form-control settings-test-input" id="cpassword" name="cpassword" value="<?php echo $cPassword; ?>">
            <?php display_error($cPasswordErr) ?>
          </div>
          <button type="submit" id="submitUpdateDisplayName" class="btn btn-primary">Save</button>
          <button type="submit" id="cancel" name="cancel" class="btn btn-secondary" value="cancel">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once("../basepagebottom.php");?>
