<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/test_user_input.php");

//starting code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (test_signin_inputs()) {
    set_session_data($loginInfo);
    header("Location:index.php");
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
</head>

<body>
  <header>
    <div class="metube_navbar">
      <a href="index.php"><h2>MeTube</h2></a>
    </div>
  </header>
  <main>
    <div class="center">
      <div class="authportal-warning"></div>
      <div class="auth-page-wrapper">
        <div class="a-section login-section a-large-margin">
          <form method="post" class="validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="a-box">
              <div class="a-box-inner a-padding-extra-large">
                <h1 class="a-small-spacing">Sign in</h1>
                <div class="a-row a-small-spacing">
                  <label for="logininfo" class="form-label a-full-width">Email or Username</label>
                  <input type="text" id="logininfo" name="logininfo" tabindex="1" maxlength="128" class="a-input-text a-full-width" value="<?php echo $loginInfo; ?>">
                  <?php display_error($loginInfoErr);?>
                </div>
                <div class="a-row a-large-spacing">
                  <label for="password" class="form-label a-full-width">Password</label>
                  <input type="password" id="password" name="password" tabindex="2" maxlength="128" class="a-input-text a-full-width" value="<?php echo $password; ?>">
                  <?php display_error($passwordErr);?>
                </div>
                <?php display_error($error);?>
                <div class="a-large-spacing">
                  <span class="button button-primary button-full-width">
                    <span class="button-inner">
                      <input type="submit" id="signInSubmit" tabindex="3" maxlength="128" class="button-input a-full-width" value="login">
                      <span class="button-text">Sign in</span>
                    </span>
                  </span>
                </div>
                <div class="divider horizontal-rule">
                  <h5>New to MeTube?</h5>
                </div>
                <div class="create_account_wrapper">
                  <span class="button button-full-width">
                    <span class="button-inner">
                      <a id="createAccountSubmit" tabindex="4" href="createAccount.php" class="button-text">Create Account</a>
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
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
