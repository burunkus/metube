<?php
//required scripts
require_once("database_queries.php");

$error = "";
$firstName = $firstNameErr = "";
$lastName = $lastNameErr = "";
$email = $emailErr = "";
$username = $usernameErr = "";
$password = $passwordErr = "";
$passwordVerify = $passVerifyErr = "";
$loginInfo = $loginInfoErr = "";
$cPassword = $cPasswordErr = "";
$displayNameNew = $displayNameErr = "";
$emailNew = $emailNewErr = "";
$usernameNew = $usernameNewErr = "";

$dfError = "email/username and/or password are incorrect";

function test_signin_inputs()
{
  global $loginInfo, $loginInfoErr, $password, $passwordErr, $dfError, $error;
  if (empty($_POST["logininfo"])) {
    $loginInfoErr = "login info is required";
    return false;
  }
  $loginInfo = $_POST["logininfo"];

  if (empty($_POST["password"])) {
    $passwordErr = "password is required";
    return false;
  }
  $password = $_POST["password"];

  if (!email_exists($loginInfo) && !user_name_exists($loginInfo)) {
    $error = $dfError;
    return false;
  }

  if (!password_valid(get_user($loginInfo)[4], $password)) {
    $error = $dfError;
    return false;
  }

  return true;
}

function edit_input($input)
{
  return htmlspecialchars(stripslashes(trim($input)));
}

function test_first_name()
{
  global $firstName, $firstNameErr;
  if (empty($_POST["firstname"])) {
    $firstNameErr = "first name is required";
    return false;
  }
  $firstName = filter_var($_POST["firstname"]);
  return true;
}

function test_last_name()
{
  global $lastName, $lastNameErr;
  if (empty($_POST["lastname"])) {
    $lastNameErr = "last name is required";
    return false;
  }
  $lastName = filter_var($_POST["lastname"]);
  return true;
}

function test_email()
{
  global $emailErr, $email;
  $valid = true;
  if (empty($_POST["email"])) {
    $emailErr = "email is required";
    $valid = false;
  } else {
    $email = edit_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      $valid = false;
    }
    if (email_exists($email)) {
      $emailErr = "email is already in use";
      $valid = false;
    }
  }
  return $valid;
}

function test_user_name()
{
  global $username, $usernameErr;
  $valid = true;
  if (empty($_POST["username"])) {
    $userNameErr = "username is required";
    $valid = false;
  } else {
    $username = edit_input($_POST["username"]);
    if (!preg_match("/^[A-Za-z0-9]*$/", $username)) {
      $userNameErr = "username must be alphanumeric";
      $valid = false;
    } else if (!preg_match("/^.{1,20}$/", $username)) {
      $userNameErr = "username must contain 1 to 20 characters";
      $valid = false;
    } else if (user_name_exists($username)) {
      $usernameErr = "username is already in use";
      $valid = false;
    }
  }
  return $valid;
}

function test_passwords()
{
  global $passwordErr, $passVerifyErr, $password, $passwordVerify;
  if (empty($_POST["password"])) {
    $passwordErr = "password is required";
    return false;
  }
  $password = $_POST["password"];
  if (!valid_password_format()) {
    return false;
  }
  if (empty($_POST["repassword"])) {
    $passVerifyErr = "please reenter your password";
    return false;
  }
  $passwordVerify = $_POST["repassword"];
  if ($password !== $passwordVerify) {
    $passVerifyErr = "the two passwords do not match";
    return false;
  }
  return true;
}

function valid_password_format()
{
  //checks for upper case letter
  global $passwordErr, $password;
  $valid = true;
  if ((!preg_match("/^[A-Za-z0-9!#$%&'*+=?@~]*$/", $password))) {
    $passwordErr += "<br>password must contain only letters numbers and special symbols from the following list";
    $passwordErr += "!#$%&'*+=?@~";
    $valid = false;
  } else {
    if (!preg_match("/^.*[A-Z].*$/", $password)) {
      $passwordErr .= "<br>password must contain at least one upper case letter";
      $valid = false;
    }
    //checks for lower case letter
    if (!preg_match("/^.*[a-z].*$/", $password)) {
      $passwordErr .= "<br>password must contain at least one lower case letter";
      $valid = false;
    }
    //checks for number
    if (!preg_match("/^.*[0-9].*$/", $password)) {
      $passwordErr .= "<br>password must contain at least one number";
      $valid = false;
    }
    //checks for special character
    if (!preg_match("/^.*[!#$%&'*+=?@~].*$/", $password)) {
      $passwordErr .= "<br>password must contain at least one special symbol from the following list:";
      $passwordErr .= "<br>&emsp;&emsp;&emsp;!#$%&'*+=?@~";
      $valid = false;
    }
    //checks for 7 - 20 characters
    if (!preg_match("/^.{7,20}$/", $password)) {
      $passwordErr .= "<br>password must contain 7 to 20 characters";
      $valid = false;
    }
  }
  return $valid;
}

function test_current_password()
{
  global $cPasswordErr, $username, $cPassword;
  if (empty($_POST["cpassword"])) {
    $cPasswordErr = "please enter current password";
    return false;
  }
  $cPassword = $_POST["cpassword"];
  if (!password_valid($username, $cPassword)) {
    $cPasswordErr = "password entered does not match current password";
    return false;
  }
  return true;
}

function test_display_name()
{
  global $displayNameNew, $displayNameErr;
  if (empty($_POST["displayname"])) {
    $displayNameErr = "this cannot be left black";
    return false;
  }
  $displayNameNew = filter_var($_POST["displayname"]);
  return true;
}

function test_new_email()
{
  global $emailNew, $emailNewErr;
  $valid = true;
  if (empty($_POST["emailnew"])) {
    $emailNewErr = "email is required";
    $valid = false;
  } else {
    $emailNew = edit_input($_POST["emailnew"]);
    if (!filter_var($emailNew, FILTER_VALIDATE_EMAIL)) {
      $emailNewErr = "Invalid email format";
      $valid = false;
    }
    if (email_exists($emailNew)) {
      $emailNewErr = "email is already in use";
      $valid = false;
    }
  }
  return $valid;
}

function test_user_name_new()
{
  global $usernameNew, $usernameNewErr;
  $valid = true;
  if (empty($_POST["usernamenew"])) {
    $usernameNewErr = "username is required";
    $valid = false;
  } else {
    $usernameNew = edit_input($_POST["usernamenew"]);
    if (!preg_match("/^[A-Za-z0-9]*$/", $usernameNew)) {
      $usernameNewErr = "username must be alphanumeric";
      $valid = false;
    } else if (!preg_match("/^.{1,20}$/", $usernameNew)) {
      $usernameNewErr = "username must contain 1 to 20 characters";
      $valid = false;
    } else if (user_name_exists($usernameNew)) {
      $usernameNewErr = "username is already in use";
      $valid = false;
    }
  }
  return $valid;
}

function test_uploader_keyword($keyword) {
  if(preg_match("/^[a-zA-Z\s]+$/", $keyword)) {
      return true;
  } else return false;
}

function test_display_media_id($id) {
  if(preg_match("/^[0-9]+$/", $id)) {
      return true;
  } else return false;
}
