<?php
 //starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/database_queries.php");
require_once("php_scripts/test_user_input.php");
require_once("php_scripts/helper_functions.php");

//starting code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $valid = true;
  if (!test_first_name()) {
    $valid = false;
  }
  if (!test_last_name()) {
    $valid = false;
  }
  if (!test_email()) {
    $valid = false;
  }
  if (!test_user_name()) {
      $valid = false;
    }
  if (!test_passwords()) {
    $valid = false;
  }
  if ($valid) {
    add_user($firstName, $lastName, $email, $username, $password);
    header("Location:login.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MeTube</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script>
    function checkClicked(cb) {
      var firstname = document.getElementById("firstname");
      var lastname = document.getElementById("lastname");
      var displayname = document.getElementById("displayname");
      if (displayname.disabled = cb.checked) {
        displayname.value = firstname.value + " " + lastname.value;
      }
    }

    function textChanged() {
      var cb = document.getElementById("check");
      if (cb.checked) {
        var firstname = document.getElementById("firstname");
        var lastname = document.getElementById("lastname");
        var displayname = document.getElementById("displayname");
        displayname.value = firstname.value + " " + lastname.value;
      }
    }
  </script>
</head>

<body>
  <header>
    <div class="metube_navbar">
      <a href="index.php"><h2>MeTube</h2></a>
    </div>
  </header>
  <main>
    <section class="center">
      <div class="create-page-warning"></div>
      <div class="create-account-page-wrapper">
        <div class="a-section a-large-margin">
          <form class="validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="a-box">
              <div class="a-box-inner a-padding-extra-large">
                <h1 class="a-small-spacing">Create Account</h1>
                <div class="a-row a-small-spacing">
                  <label for="firstname" class="form-label a-full-width">First Name</label>
                  <input type="text" name="firstname" id="firstname" tabindex="1" maxlength="50" class="a-input-text a-full-width" value="<?php echo $firstName; ?>">
                  <?php display_error($firstNameErr) ?>
                </div>
                <div class="a-row a-small-spacing">
                  <label for="lastname" class="form-label a-full-width">Last Name</label>
                  <input type="text" name="lastname" id="lastname" tabindex="2" maxlength="50" class="a-input-text a-full-width" value="<?php echo $lastName; ?>">
                  <?php display_error($lastNameErr) ?>
                </div>
                <div class="a-row a-small-spacing">
                  <label for="email" class="form-label a-full-width">Email</label>
                  <input type="email" name="email" id="email" tabindex="3" maxlength="100" class="a-input-text a-full-width" value="<?php echo $email; ?>"><br>
                  <?php display_error($emailErr) ?>
                </div>
                <div class="a-row a-small-spacing">
                  <label for="username" class="form-label a-full-width">Username</label>
                  <input type="text" name="username" id="username" tabindex="3" maxlength="20" class="a-input-text a-full-width" value="<?php echo $username; ?>"><br>
                  <?php display_error($usernameErr) ?>
                </div>
                <div class="a-row a-small-spacing">
                  <label for="password" class="form-label a-full-width">Password</label>
                  <input type="password" name="password" id="password" tabindex="4" maxlength="20" class="a-input-text a-full-width" value="<?php echo $password; ?>"><br>
                  <?php display_error($passwordErr) ?>
                </div>
                <div class="a-row a-small-spacing">
                  <label for="password2" class="form-label a-full-width">Reenter Password</label>
                  <input type="password" name="repassword" id="password2" tabindex="5" maxlength="20" class="a-input-text a-full-width" value="<?php echo $passwordVerify; ?>"><br>
                  <?php display_error($passVerifyErr) ?>
                </div>
                <div class="create_account_wrapper">
                  <span class="button button-full-width">
                    <span class="button-inner">
                      <input type="submit" id="createAccountSubmit" tabindex="8" value="Create Account">
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>
  <footer>
    <div class="center">
      <div class="footer_inner">
        <p>@MeTube 2019</p>
        <p>Database Management Systems Project. Spring 2019</p>
      </div>
    </div>
  </footer>
</body>

</html>
