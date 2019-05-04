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
  if (!test_first_name()) {
    $valid = false;
  }
  if (!test_last_name()) {
    $valid = false;
  }
  if ($valid) {
    if ($firstName != $_SESSION["firstname"]) {
      change_first_name($email, $firstName);
      $_SESSION["firstname"] = $firstName;
    }
    if ($lastName  != $_SESSION["lastname"]) {
      change_last_name($email, $lastName);
      $_SESSION["lastname"] = $lastName;
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
      <form id="updateDisplayName" class="settings-changes-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <h1>Change Name</h1>
          <div class="form-group col-md-7">
            <label for="firstname">First Name</label>
            <input type="text" class="form-control settings-test-input" name="firstname" value="<?php echo $firstName;?>">
            <?php display_error($firstNameErr) ?>
          </div>
          <div class="form-group col-md-7">
            <label for="lastname">Last Name</label>
            <input type="text" class="form-control settings-test-input" name="lastname" value="<?php echo $lastName;?>">
            <?php display_error($lastNameErr) ?>
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
<?php require_once("../basepagebottom.php"); ?>
