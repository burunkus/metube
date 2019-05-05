<?php
require_once("sql_functions.php");
require_once("table_values.php");

function hash_password($userName, $password)
{
  return password_hash($userName . $password, PASSWORD_BCRYPT);
}

function logged_in()
{
  return !empty($_SESSION["email"]);
}

function display_info($info)
{
  if (logged_in()) {
    echo $info;
  }
}

function set_session_data($loginInfo)
{
  $user = get_user($loginInfo);

  $_SESSION["user_id"] = $user[0];
  $_SESSION["firstname"] = $user[1];
  $_SESSION["lastname"] = $user[2];
  $_SESSION["email"] = $user[3];
  $_SESSION["username"] = $user[4];
  $_SESSION["displayname"] = $user[6];
}

function display_error($error)
{
  if (strlen($error) != 0) {
    echo '<span class="inputerror">*';
    echo $error;
    echo '</span><br>';
  }
}

function set_contact_button_text($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactBlocked:
      echo "Add To Contacts";
      break;
    case $contactContact:
    case $contactFriend:
      echo "Remove From Contacts";
      break;
  }
}

function set_block_button_text($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactContact:
    case $contactFriend:
      echo "Block";
      break;
    case $contactBlocked:
      echo "Unblock";
      break;
  }
}

function set_friend_button_text($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactContact:
    case $contactBlocked:
      echo "Add To Friends";
      break;
    case $contactFriend:
      echo "Remove From Friends";
      break;
  }
}

function set_contact_option($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactBlocked:
      echo "add";
      break;
    case $contactContact:
    case $contactFriend:
      echo "remove";
      break;
  }
}

function set_block_option($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactContact:
    case $contactFriend:
      echo "block";
      break;
    case $contactBlocked:
      echo "unblock";
      break;
  }
}

function set_friend_option($contactType)
{
  global $contactNone, $contactContact, $contactFriend, $contactBlocked;

  switch ($contactType) {
    case $contactNone:
    case $contactContact:
    case $contactBlocked:
      echo "makefriend";
      break;
    case $contactFriend:
      echo "removefriend";
      break;
  }
}

function upload_error($result)
{
  $message = "";
  //view erorr description in http://us2.php.net/manual/en/features.file-upload.errors.php
  switch ($result){
    case UPLOAD_ERR_INI_SIZE:
      $message .= "The file exceeds the maximum you can upload";
      break;
    case UPLOAD_ERR_FORM_SIZE:
      $message .= "The file exceeds the stated maximum 20MB";
      break;
    case UPLOAD_ERR_PARTIAL:
      $message .= "The file was not fully uploaded";
      break;
    case UPLOAD_ERR_NO_FILE:
      $message .= "No file was uploaded";
      break;
    case UPLOAD_ERR_NO_TMP_DIR:
      $message .= "Missing a temporary folder";
      break;
    case UPLOAD_ERR_CANT_WRITE:
      $message .= "Failed to write file to disk";
      break;
    case UPLOAD_ERR_EXTENSION:
      $message .= "File upload failed by extension";
      break;
  }
  return $message;
}

function extract_date($date){
  $matches = null;
  preg_match('/^([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})/',$date,$matches);
  return $matches[2] . "-" . $matches[3] . "-" . $matches[1];
}
