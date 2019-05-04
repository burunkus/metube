<?php
//required scripts
require_once("sql_functions.php");
require_once("helper_functions.php");

function email_exists($email)
{
  global $userTable, $userEmail;
  $query = "Select $userEmail from $userTable where $userEmail = '$email'";
  $result = get_result($query);
  return get_row($result) ? true : false;
}

function user_name_exists($userNameTest)
{
  global $userTable, $userName;
  $query = "Select $userName from $userTable where $userName = '$userNameTest'";
  $result = get_result($query);
  return get_row($result) ? true : false;
}

function add_user($firstname, $lastname, $email, $username, $password)
{
  global $userTable, $userFirstName, $userLastName, $userEmail, $userName, $userPass,  $userDisplayName;
  $passwordHash = hash_password($username, $password);
  $displayName = $firstname . " " . $lastname;
  $query = "INSERT INTO $userTable ($userFirstName,$userLastName,$userEmail,$userName, $userPass, $userDisplayName) " .
    "VALUES ('$firstname','$lastname','$email','$username','$passwordHash','$displayName')";
  get_result($query);
}

function get_user($loginInfo)
{
  global $userTable, $userEmail, $userName, $userID;
  $query = gettype($loginInfo) == 'integer' ?
    "Select * from $userTable where $userID = $loginInfo" :
    "Select * from $userTable where $userEmail = '$loginInfo' OR $userName = '$loginInfo'";
  $result = get_result($query);
  return get_row($result);
}

function get_users($name)
{
  global $userTable, $userEmail, $userName, $userFirstName, $userLastName, $userDisplayName;
  $query = "Select * from $userTable where $userEmail LIKE '%$name%' OR $userName LIKE '%$name%'" .
    "OR $userDisplayName LIKE '%$name%' OR CONCAT($userFirstName, ' ', $userLastName) LIKE '%$name%'";
  $result = get_result($query);
  return $result;
}

function update($table, $column, $data, $conditionCol, $colData)
{
  $query = "UPDATE $table SET $column = '$data' WHERE $conditionCol = '$colData'";
  get_result($query);
}

function change_first_name($email, $firstName)
{
  global $userTable, $userFirstName, $userEmail;
  update($userTable, $userFirstName, $firstName, $userEmail, $email);
}

function change_last_name($email, $lastName)
{
  global $userTable, $userLastName, $userEmail;
  update($userTable, $userLastName, $lastName, $userEmail, $email);
}

function change_display_name($email, $displayName)
{
  global $userTable, $userDisplayName, $userEmail;
  update($userTable, $userDisplayName, $displayName, $userEmail, $email);
}

function change_email($email, $emailNew)
{
  global $userTable, $userEmail;
  update($userTable, $userEmail, $emailNew, $userEmail, $email);
}

function change_username($email, $usernameNew)
{
  global $userTable, $userEmail, $userName;
  update($userTable, $userName, $usernameNew, $userEmail, $email);
}

function change_password($username, $password, $email)
{
  global $userTable, $userPass, $userEmail;
  $newPassword = hash_password($username, $password);
  update($userTable, $userPass, $newPassword, $userEmail, $email);
}

function password_valid($username, $password)
{
  global $userTable, $userName, $userPass;
  $query = "Select $userPass from $userTable where $userName = '$username'";
  $result = get_result($query);
  return password_verify($username . $password, get_row($result)[0]);
}

function contact_type($userID1, $userID2)
{
  global $contactTable, $contactUser1, $contactUser2, $contactCategory;
  $query = "Select $contactCategory from $contactTable where $contactUser1 = $userID1 AND $contactUser2 = $userID2";
  $result = get_row(get_result($query));
  return $result ? $result[0] : 0;
}

function remove_contact($userID1, $userID2)
{
  global $contactTable, $contactUser1, $contactUser2;
  $query = "DELETE FROM $contactTable WHERE $contactUser1 = $userID1 " .
    "AND $contactUser2 = $userID2";
  get_result($query);
}

function update_contact($username, $personusername, $contactType)
{
  global $contactRemove, $contactTable, $contactCategory, $contactUser1, $contactUser2;
  global $contactBlocked, $contactNone;
  global $contactMessage;

  $userID1 = get_user($username)[0];
  $userID2 = get_user($personusername)[0];

  if ($userID1 == $userID2) {
    return;
  }

  $contactTypeU1U2 = contact_type($userID1, $userID2);
  $contactTypeU2U1 = contact_type($userID2, $userID1);
  $updateQuery = "UPDATE $contactTable SET $contactCategory = $contactType WHERE $contactUser1 = $userID1 " .
    "AND $contactUser2 = $userID2";
  $insertQuery = "INSERT INTO $contactTable ($contactUser1,$contactUser2,$contactCategory) " .
    "VALUES ($userID1,$userID2,$contactType)";
  if($contactType == $contactRemove){
    remove_contact($userID1, $userID2);
    $contactMessage = "$personusername has been removed";
  } else if($contactType == $contactBlocked) {
    if($contactTypeU1U2 == $contactNone) {
      get_result($insertQuery);
    } else {
      get_result($updateQuery);
    }
    if($contactTypeU2U1 != $contactBlocked){
      remove_contact($userID2, $userID1);
    }
    $contactMessage = "$personusername has been blocked";
  } else if($contactTypeU2U1 != $contactBlocked) {
    if($contactTypeU1U2 == $contactNone) {
      get_result($insertQuery);
      $contactMessage = "$personusername has been added";
    } else {
      get_result($updateQuery);
      $contactMessage = "$personusername has been updated";
    }
  } else if($contactTypeU2U1 = $contactBlocked) {
    $contactMessage = "$personusername has blocked you";
  }
}

function upload_media($media_type, $media_format, $media_title, $media_description, $media_access, $media_discussion_type, $rate_media, $file_size, $file_path, $uploaded_by) {

  global $mediaTable, $mediaID, $mediaUploader, $mediaTitle, $mediaDescription, $mediaType, $mediaExtension, $mediaShareType, $mediaDiscussionType, $mediaRatingType, $mediaViewCount, $mediaPath, $mediaFileSize, $mediaUploadTime;

  $query = "INSERT INTO $mediaTable ($mediaUploader, $mediaTitle, $mediaDescription, $mediaType, $mediaExtension, $mediaShareType, $mediaDiscussionType, $mediaRatingType, $mediaViewCount, $mediaPath, $mediaFileSize, $mediaUploadTime) VALUES ('$uploaded_by', '$media_title', '$media_description', '$media_type', '$media_format', '$media_access', '$media_discussion_type', '$rate_media', '0', '$file_path', '$file_size', now())";

  get_result($query);

  //retrieve the ID of the just inserted media
  $query2 = "SELECT $mediaID FROM $mediaTable WHERE $mediaUploader = '$uploaded_by' ORDER BY $mediaUploadTime DESC LIMIT 1";
  $last_inserted_media_id = get_row(get_result($query2));
  return $last_inserted_media_id[0];
}

function get_keyword_count() {
  $query = "SELECT keyword, Count(*) as amount FROM media_keywords GROUP BY keyword;";
  return get_result($query);
}

function insert_other_templates($media_id, $media_category, $media_keywords, $usersToBlock) {
  /* inserts media categories and media keywords into the category and keyword table*/

  global $mediaID, $mediaCategoryTable, $mediaCategory, $mediaKeyWordsTable, $mediaKeyWordsID, $mediaKeyWord;

  foreach ($media_category as $category) {
    if($category == 0){
      continue;
    }
    $query1 = "INSERT INTO $mediaCategoryTable ($mediaID, $mediaCategory)
               VALUES ('$media_id', '$category')
              ";
    get_result($query1);
  }

  foreach ($media_keywords as $keyword) {
    $query2 = "INSERT INTO $mediaKeyWordsTable ($mediaKeyWordsID, $mediaKeyWord)
               VALUES ('$media_id', '$keyword')
              ";
    get_result($query2);
  }

  //insert into media blocks
  $arrayLength = count($usersToBlock);
  if($arrayLength > 0) {
    for($i = 0; $i < $arrayLength; $i++) {
      print_r($usersToBlock[$i]);
      $query3 = "INSERT INTO `media_blocks` (`media_id`, `user_id`) VALUES ($media_id, $usersToBlock[$i]) ";
      get_result($query3);
    }
  }
}

function get_most_viewed($user=0, $isBlocked="") {

  //if not logged in, display all media that was shared with everyone
  if(empty($user) && empty($isBlocked)) {
    $query = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time` FROM `media` WHERE `share_type` = 0 ORDER BY `view_count` DESC";
    return get_result($query);
  } else {
    // confirm that a user is not blocked from viewing any media and only display the media a user is allowed to access
    $query2 = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM (SELECT * FROM media WHERE share_type = 0 UNION (SELECT * FROM media WHERE share_type = 1 AND uploader IN (SELECT contact_1 from contacts WHERE contact_2 = $user and category = 2))) as new_table WHERE new_table.media_id NOT IN (SELECT media_id from media_blocks WHERE user_id = $user) ORDER by `view_count` DESC LIMIT 15";
    return get_result($query2);
  }

}

function get_recently_uploaded($user=0, $isBlocked="") {
  //if not logged in, display all media that was shared with everyone and was uploaded within the last 30 days.
  /*Note: a user who is blocked can still view a media he is blocked from viewing when they are not logged in. To prevent this users should note that blocking a user from viewing a file only works when that file is not public and you are friends
  */

  if(empty($user) && empty($isBlocked)) {
    $query1 = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM `media` WHERE `share_type` = 0 AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= `upload_time` ORDER BY DATE(`upload_time`) DESC";
    return get_result($query1);
  } else {
    //if logged in, confirm that a user is not blocked from viewing any media file and only display files they are allowed to view which are public and by their friends if they are friends

    $query2  = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM (SELECT * FROM media WHERE share_type = 0 UNION (SELECT * FROM media WHERE share_type = 1 AND uploader IN (SELECT contact_1 from contacts WHERE contact_2 = $user and category = 2))) as new_table WHERE new_table.media_id NOT IN (SELECT media_id from media_blocks WHERE user_id = $user) ORDER by `upload_time` DESC";
    return get_result($query2);
  }
}

function get_home($user=0, $isBlocked="") {
  //if not logged in display media files that are public according to upload time
  if(empty($user) && empty($isBlocked)) {
    $query = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM `media` WHERE `share_type` = 0 ORDER BY `upload_time` DESC";
    return get_result($query);
  } else {
    //if logged in, display media files from a users friends only when their friends have not blocked them. This is a user's timeline

    $query2 = "SELECT `media_id`, `uploader`, `title`, `media_description`, `media_type`, `extension`, `file_path`, `view_count`, `upload_time`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM (SELECT * FROM media WHERE (share_type = 0 OR share_type = 1) AND uploader IN (SELECT contact_1 from contacts WHERE contact_2 = $user and category = 2)) as new_table WHERE new_table.media_id NOT IN (SELECT media_id from media_blocks WHERE user_id = $user) ORDER by `upload_time` DESC";
    return get_result($query2);
  }
}

function have_friends($user_id) {
  $query ="SELECT * FROM `contacts` WHERE `contact_2` = $user_id and `category` = 2";
  $result = get_result($query);
  return get_row($result) ? 1 : 0;
}

function get_search($keywords, $user=0, $isBlocked="") {
  if(empty($user) && empty($isBlocked)) {
    $query = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
      "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM `media` WHERE " .
      "`share_type` = 0 AND `media_id` in (SELECT `media_id` from `media_keywords` where `keyword` RLIKE ".
      "('$keywords')) ";
    return get_result($query);
  } else {
    //if logged in, display media files from a users friends only when their friends have not blocked them.

    $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
      "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM (SELECT * FROM ".
      "media WHERE share_type = 0 UNION (SELECT * FROM media WHERE share_type = 1 AND uploader IN (SELECT ".
      "contact_1 from contacts WHERE contact_2 = $user and category = 2))) as new_table WHERE new_table.media_id ".
      "NOT IN (SELECT media_id from media_blocks WHERE user_id = $user) and `media_id` in (SELECT `media_id` from ".
      "`media_keywords` where `keyword` RLIKE '($keywords)') ";
    return get_result($query2);
  }
}

function get_all_media($user) {
  global $mediaTable, $mediaUploader;
  $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
  "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM  $mediaTable ".
  "WHERE $mediaUploader = $user";
  return get_result($query2);
}

function get_all_media_videos($user) {
  global $mediaTable, $mediaUploader;
  $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
  "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM  $mediaTable ".
  "WHERE $mediaUploader = $user AND `media_type` = 'video'";
  return get_result($query2);
}

function get_all_media_images($user) {
  global $mediaTable, $mediaUploader;
  $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
  "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM  $mediaTable ".
  "WHERE $mediaUploader = $user AND `media_type` = 'image'";
  return get_result($query2);
}

function get_all_media_audio($user) {
  global $mediaTable, $mediaUploader;
  $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
  "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM  $mediaTable ".
  "WHERE $mediaUploader = $user AND `media_type` = 'audio'";
  return get_result($query2);
}

function get_all_media_anime($user) {
  global $mediaTable, $mediaUploader;
  $query2 = "SELECT `media_id`, `title`, `media_description`, `media_type`, `extension`, `file_path`, ".
  "`view_count`, `upload_time`, `file_size`, DATEDIFF(CURDATE(), upload_time) as date_diff FROM  $mediaTable ".
  "WHERE $mediaUploader = $user AND `media_type` = 'anime'";
  return get_result($query2);
}

function get_media($media_id, $user_id, $logged_in_status) {
  $query = "SELECT share_type FROM `media` WHERE `media_id` = $media_id";
  $share_type = get_result($query);
  $share_type = get_row($share_type)[0];

  if($logged_in_status) {
    //check if private and the viewer is not the uploader of the media
    if($share_type == 2) {
      $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.discussion_type, media.rating_type, users.display_name FROM `media` INNER JOIN `users` ON media.uploader = users.user_id WHERE media.share_type = 2 AND media.media_id = $media_id AND media.uploader <> $user_id";
      $result = get_result($query);
      return $result;
      /**
      if($result) {
        return 0;  //you can't view this media, it is private and you are not the owner
      } else {
        return $result;  //private media being viewed by the owner/uploader
      }**/

    // check if friends with the uploader and the viewer is not blocked
    } else if($share_type == 1)  {
      $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.discussion_type, media.rating_type, users.display_name FROM `media` INNER JOIN `users` ON media.uploader = users.user_id WHERE `share_type` = 1 AND `uploader` IN (SELECT `contact_1` FROM `contacts` WHERE `contact_2` = $user_id and `category` = 2) AND `media_id` NOT IN (SELECT `media_id` FROM `media_blocks` WHERE `user_id` = $user_id) AND `media_id` = $media_id";
      $result = get_result($query);
      return $result;
      /**
      if($result) {
        return $result;
      } else {
        return 0; //you can't view this media because you are blocked
      }**/
    }
    //check if uploader shared with everyone and a user has been blocked from viewing the media
    else if($share_type == 0) {
      $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.discussion_type, media.rating_type, users.display_name FROM `media` INNER JOIN `users` ON media.uploader = users.user_id WHERE `share_type` = 0 AND `media_id` = $media_id AND `media_id` NOT IN (SELECT `media_id` FROM `media_blocks` WHERE `user_id` = $user_id)";
      $result = get_result($query);
      return $result;
      /*
      if($result) {
        return $result;
      } else {
        return 0; //you can't view this file
      }*/
    }
  }
  //if a user is not logged in they can view any media that is public even though the uploader has blocked them. blocking basically works when the share type is not public
  else {
    $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.discussion_type, media.rating_type, users.display_name FROM `media` INNER JOIN `users` ON media.uploader = users.user_id WHERE `share_type` = 0 AND `media_id` = $media_id AND `media_id` = $media_id";
    $result = get_result($query);
    return $result;
  }
}

function download_media($id) {
  $query = "SELECT `file_path`FROM `media` WHERE media_id = $id";
  return get_result($query);
}

function increase_view_count($media_id) {
  $query = "UPDATE `media` SET `view_count` = `view_count` + 1 WHERE `media_id` = $media_id";
  return get_result($query);
}

function check_if_already_reviewed($media_id, $user_id) {
  $query = "SELECT `user_id`  FROM `media_ratings` WHERE `user_id` = $user_id AND `media_id` = $media_id";
  $result = get_result($query);
  return get_row($result) ? true : false;
}

function add_review($media_id, $user_id, $rate_value) {
  $query = "INSERT INTO `media_ratings` (`media_id`, `user_id`, `rating`) VALUES ($media_id, $user_id, $rate_value)";
  return get_result($query);
}

function update_review($media_id, $user_id, $rate_value) {
  $query = "UPDATE `media_ratings` SET `rating` = $rate_value WHERE `media_id` = $media_id AND `user_id` = $user_id";
  return get_result($query);
}

function get_reviews($media_id) {
  $query = "SELECT AVG(`rating`) as `ratedAmount` FROM `media_ratings` WHERE `media_id` = $media_id";
  return get_result($query);
}

function get_channel_id($media_id) {
  $query = "SELECT `uploader` FROM `media` WHERE `media_id` = $media_id";
  return get_result($query);
}

function subscribe($channel_id, $subscriber_id) {
  $query = "INSERT INTO `subscriptions` (`contact_1`, `contact_2`) VALUES ($channel_id, $subscriber_id)";
  return get_result($query);
}

function unsubscribe($channel_id, $subscriber_id) {
  $query = "DELETE FROM `subscriptions` WHERE `contact_1` = $channel_id AND `contact_2` = $subscriber_id";
  return get_result($query);
}

function get_number_of_subscribers($channel_id) {
  $query = "SELECT COUNT(*) as numberOfSubscribers FROM `subscriptions` WHERE contact_1 = $channel_id";
  return get_result($query);
}

function checkIfSubscribed($channel_id, $user_id) {
  $query = "SELECT `contact_2` FROM `subscriptions` WHERE `contact_1` = $channel_id";
  $result = get_result($query);
  return get_row($result) ? 1 : 0; //if it break come back here
}

function get_subscriptions($user_id) {
  $query = "SELECT user_id, display_name FROM ((SELECT contact_1 FROM subscriptions WHERE ".
    "contact_2 = $user_id) as user_subscriptions INNER JOIN users ON users.user_id = ".
    "user_subscriptions.contact_1)";
  return get_result($query);
}

function get_categories($media_id) {
  global $mediaCategoryTable, $mediaCategory, $mediaID;
  $query = "SELECT $mediaCategory FROM $mediaCategoryTable WHERE $mediaID = $media_id";
  return get_result($query);
}

function check_media_access($media_id, $user_id) {
  //returns 1 if you are blocked, returns 0 if you are not blocked
  $query = "SELECT * FROM `media_blocks` WHERE `media_id` = $media_id AND `user_id` = $user_id";
  $result = get_result($query);
  return get_row($result) ? 1 : 0;
}

function get_blocked_users($userid) {
  $query = "SELECT media.media_id, media.title, media.uploader, media_blocks.user_id,users.first_name, users.last_name FROM `media_blocks` INNER JOIN `media` ON media_blocks.media_id = media.media_id INNER JOIN `users` ON media_blocks.user_id = users.user_id WHERE media.uploader = $userid";
  return get_result($query);

}

function block_user_access($media_id, $user_to_block) {
  $query = "INSERT INTO `media_blocks` (`media_id`, `user_id`) VALUES ($media_id, $user_to_block) ";
  return get_result($query);
}

function unblock_user($media_id, $user_to_unblock) {
  $query = "DELETE FROM `media_blocks` WHERE `media_id` = $media_id AND `user_id` = $user_to_unblock";
  return get_result($query);
}

function create_conversation($conv_name,$conv_type) {
  $query = "INSERT INTO conversations (conv_name,conv_type) " .
  "VALUES ('$conv_name',$conv_type)";
  get_result($query);
  return get_inserted_id();
}

function get_media_conversation_id($media_id) {
  $commentIDQuerey = "SELECT conv_id FROM comment_section WHERE media_id = $media_id";
  $result = get_result($commentIDQuerey);
  $idRow = get_row($result);
  $id = 0;
  if(!$idRow){
    $id= create_conversation('Comments',1);
    $query =  "INSERT INTO comment_section (media_id,conv_id) " .
    "VALUES ($media_id,$id)";
    get_result($query);
  } else {
    $id = $idRow[0];
  }
  return $id;
}

function get_conversations($conversation_id, $ordering) {
  $query = "SELECT display_name, comm.user_id, conv_id, write_time, comment ".
    "FROM ((SELECT * FROM comments WHERE conv_id = $conversation_id) ".
    "as comm INNER JOIN users on comm.user_id = users.user_id) ORDER BY write_time $ordering";
  return get_result($query);
}

function get_comment_section($media_id) {
  $id = get_media_conversation_id($media_id);
  return get_conversations($id, 'DESC');
}

function add_comment($userID,$conversation_id,$comment) {
  $query = "INSERT INTO comments (user_id,conv_id,write_time,comment) " .
    "VALUES ($userID,$conversation_id,now(),'$comment')";
  return get_result($query) ? 1 : 0;
}

function delete_comment($userID, $writeTime) {
  $query = "DELETE FROM `comments` WHERE `user_id` = $userID AND `write_time` = '$writeTime'";
  return get_result($query) ? 1 : 0;
}

/**
function get_added_comment($userID,$conversation_id) {
  $query = "SELECT comments.user_id, comments.conv_id, comments.write_time, comments.comment, users.first_name, users.last_name, users.user_name FROM `comments` INNER JOIN users ON comments.user_id = users.user_id WHERE comments.user_id = 2 AND comments.conv_id = 10 ORDER BY comments.write_time DESC LIMIT 1";
  return get_result($query);
}**/

function get_user_messages($userID){
  $query = "SELECT comments.user_id, comments.conv_id, comments.comment FROM comments INNER JOIN ".
    "(SELECT MAX(write_time) as last_time, conv_id FROM comments WHERE conv_id IN (SELECT conv_id ".
    "FROM messages WHERE contact_1=$userID OR contact_2=$userID) GROUP BY conv_id) as tandc ON comments.conv_id ".
    "WHERE comments.write_time=tandc.last_time;";
  return get_result($query);
}

function get_messages($userid1,$userid2) {
  $query = "SELECT conv_id, contact_1, contact_2 FROM messages WHERE " .
    "(contact_1 = $userid1 AND contact_2 = $userid2) OR (contact_1 = $userid2 AND contact_2 = $userid1)";
  $result = get_result($query);
  $idRow = get_row($result);
  if(!$idRow){
    return null;
  }
  return get_conversations($idRow[0],'ASC');
}

function send_message($userid1,$userid2,$message) {
  $query = "SELECT conv_id FROM messages WHERE " .
    "(contact_1 = $userid1 AND contact_2 = $userid2) OR (contact_1 = $userid2 AND contact_2 = $userid1)";

  $result = get_result($query);
  $idRow = get_row($result);
  $id = 0;
  if(!$idRow){
    $id = create_conversation("message",0);
    $query = "INSERT INTO messages(contact_1,contact_2,conv_id) VALUES ($userid1,$userid2,$id)";
    get_result($query);
  } else {
    $id = $idRow[0];
  }

  add_comment($userid1,$id,$message);
}

function add_person_to_group($userID, $groupID) {
  $searchQuery = "SELECT user_id FROM group_members WHERE group_id=$groupID ".
    "AND user_id=$userID";
  $result = get_result($searchQuery);
  if(get_row($result)){
    return 0;
  }
  $query = "INSERT INTO group_members (group_id,user_id) VALUES ($groupID,$userID)";
  get_result($query);
  return 1;
}

function create_group($userID, $groupName) {
  $query = "INSERT INTO groups (group_name) VALUES ('$groupName')";
  get_result($query);

  $groupID = get_inserted_id();
  add_person_to_group($userID,$groupID);
}

function create_group_conversation($groupID, $name) {
  $convID = create_conversation($name,2);
  $query = "INSERT INTO group_conversations (conv_id, group_id) " .
    "VALUES ($convID,$groupID)";
  get_result($query);
}

function get_group_conversations($groupID) {
  $query = "SELECT id_table.conv_id, conversations.conv_name FROM ".
    "((SELECT conv_id FROM group_conversations WHERE group_id = $groupID) as " .
    "id_table INNER JOIN conversations on conversations.conv_id = id_table.conv_id)";

  return get_result($query);
}

function create_playlist($user_id, $playlist_name) {
  $query = "INSERT INTO `playlists` (`user_id`, `playlist_name`) VALUES ($user_id, '$playlist_name')";
  return get_result($query);
}

function add_to_playlist($playlistID, $mediaID) {
  $query = "INSERT INTO `playlist_media` (`playlist_id`, `media_id`) VALUES ($playlistID, $mediaID)";
  return get_result($query);
}

function get_playlist($user_id) {
  $query = "SELECT playlists.playlist_id, playlists.playlist_name FROM `playlists` INNER JOIN `playlist_media` ON playlists.playlist_id = playlist_media.playlist_id WHERE user_id = $user_id";
  return get_result($query);
}

function all_playlist($user_id) {
  $query = "
  SELECT playlists.playlist_id, playlists.playlist_name, playlist_media.media_id, media.title, media.media_description, media.media_type FROM `playlists` INNER JOIN `playlist_media` ON playlists.playlist_id = playlist_media.playlist_id INNER JOIN `media` ON media.media_id = playlist_media.media_id WHERE `user_id` = $user_id ";
  return get_result($query);
}

function remove_from_playlist($playlistID, $mediaID) {
  $query = "DELETE FROM `playlist_media` WHERE `media_id` = $mediaID AND `playlist_id` = $playlistID";
  return get_result($query);
}

function check_if_already_in_playlist($playlistID, $userID, $playlistName) {
  $query = "SELECT * FROM `playlists` WHERE `user_id` = $userID AND `playlist_id` = $playlistID AND `playlist_name` = '$playlistName'";
  $result = get_result($query);
  return get_row($result) ? 1 : 0;
}

function create_favorite($user_id, $favorite_name) {
  $query = "INSERT INTO `favorite` (`user_id`, `favorite_name`) VALUES ($user_id, '$favorite_name')";
  return get_result($query);
}

function add_to_favorite($favorite_id, $media_id) {
  $query = "INSERT INTO `favorite_media` (`favorite_id`, `media_id`) VALUES ($favorite_id, $media_id)";
  return get_result($query);
}

function get_favorite($user_id) {
  $query = "SELECT favorite.favorite_id, favorite.favorite_name FROM `favorite` INNER JOIN `favorite_media` ON favorite.favorite_id = favorite_media.favorite_id WHERE user_id = $user_id";
  return get_result($query);
}

function all_favorite($user_id) {
  $query = "
  SELECT favorite.favorite_id, favorite.favorite_name, favorite_media.media_id, media.title, media.media_description, media.media_type FROM `favorite` INNER JOIN `favorite_media` ON favorite.favorite_id = favorite_media.favorite_id INNER JOIN `media` ON media.media_id = favorite_media.media_id WHERE `user_id` = $user_id ";
  return get_result($query);
}

function remove_from_favorite($favoriteID, $mediaID) {
  $query = "DELETE FROM `favorite_media` WHERE `media_id` = $mediaID AND `favorite_id` = $favoriteID";
  return get_result($query);
}

function check_if_already_in_favorite($favoriteID, $userID, $favoriteName) {
  $query = "SELECT * FROM `favorite` WHERE `favorite_name` = '$favoriteName' AND  `user_id` = $userID AND `favorite_id` = $favoriteID";
  $result = get_result($query);
  return get_row($result) ? 1 : 0;
}

function get_groups($userID) {
  $query = "SELECT group_name, groups.group_id FROM ((SELECT group_id FROM ".
    "group_members WHERE user_id = $userID) as user_groups INNER JOIN groups on ".
    "groups.group_id = user_groups.group_id)";
  return get_result($query);
}

function get_other_contact($userID,$convID){
  $query = "SELECT contact_1, contact_2 FROM messages WHERE conv_id = $convID";
  $result = get_result($query);
  $row = get_row($result);
  if($row[0] == $userID){
    return $row[1];
  }
  return $row[0];
}

function get_similar_media($mediaID) {
  $query = "SELECT media.media_id, media.title, media.media_type FROM ((SELECT media_id ".
    "FROM media_categories WHERE category IN (SELECT category FROM media_categories WHERE ".
    "media_id = $mediaID) AND media_id <> $mediaID) UNION (SELECT media_id FROM media_keywords WHERE keyword ".
    "IN (SELECT keyword FROM media_keywords WHERE media_id = $mediaID) AND media_id <> $mediaID)) as similar ".
    "INNER JOIN media on similar.media_id = media.media_id LIMIT 10 ";
  return get_result($query);
}

function best_of_metube_photos() {
  //selects based on the number of views, if two files have the same number of views selects based on ratings
  $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.upload_time, DATEDIFF(CURDATE(), media.upload_time) as date_diff, media_ratings.rating FROM `media` INNER JOIN `media_ratings` ON media.media_id = media_ratings.media_id WHERE `share_type` = 0 AND media.media_type = 'image' ORDER BY media.view_count DESC, media_ratings.rating DESC";
  return get_result($query);
}

function best_of_metube_videos() {
  $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.upload_time, DATEDIFF(CURDATE(), media.upload_time) as date_diff, media_ratings.rating FROM `media` INNER JOIN `media_ratings` ON media.media_id = media_ratings.media_id WHERE `share_type` = 0 AND media.media_type = 'video' ORDER BY media.view_count DESC, media_ratings.rating DESC";
  return get_result($query);
}

function best_of_metube_gifs() {
  $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.upload_time, DATEDIFF(CURDATE(), media.upload_time) as date_diff, media_ratings.rating FROM `media` INNER JOIN `media_ratings` ON media.media_id = media_ratings.media_id WHERE `share_type` = 0 AND media.media_type = 'anime' ORDER BY media.view_count DESC, media_ratings.rating DESC";
  return get_result($query);
}

function best_of_metube_audios() {
  $query = "SELECT media.media_id, media.uploader, media.title, media.media_description, media.media_type, media.extension, media.file_path, media.view_count, media.upload_time, DATEDIFF(CURDATE(), media.upload_time) as date_diff, media_ratings.rating FROM `media` INNER JOIN `media_ratings` ON media.media_id = media_ratings.media_id WHERE `share_type` = 0 AND media.media_type = 'audio' ORDER BY media.view_count DESC, media_ratings.rating DESC";
  return get_result($query);
}
