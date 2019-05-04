<?php
session_start();
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/database_queries.php");

if($_POST["type"] == "updateCount") {
    $media_id = $_POST["media_id"];
    $result = increase_view_count($media_id);
    echo json_encode("increased count");
}

if($_POST["type"] == "updateReview") {
    if(!logged_in()) {
        echo json_encode(array("message" => "-1"));
    } else if($_POST["allow_rating"] == 1) {
        echo json_encode(array("message" => "-2"));
    } else {
        $media_id = $_POST["media_id"];
        $rate_value = $_POST["rate_value"];
        $user_id = $_POST["user_id"];
        $result = check_if_already_reviewed($media_id, $user_id);
        if($result) {
            $result = update_review($media_id, $user_id, $rate_value);
            echo json_encode(array("message" => "5"));
        } else {
            $result = add_review($media_id, $user_id, $rate_value);
            echo json_encode(array("message" => "6"));
        }
    }
}

if($_POST["type"] == "subscribe") {
    if(!logged_in()) {
        echo json_encode(array("message" => "0"));
    } else {
        $media_id = $_POST["media_id"];
        $channel_id_result = get_channel_id($media_id);
        $channel_id = get_row($channel_id_result)[0];
        $subscribe_status = $_POST["status"];
        if($subscribe_status == "unsubscribe") {
            //they are subscribed so alert the user to unsubscribe
            //echo json_encode("do you want to unsubscribe?");
            echo json_encode(array("message" => "1"));
        } elseif ($subscribe_status == "subscribe") {
            $media_id = $_POST["media_id"];
            $subscriber_id = $_POST["subscriber_id"];
            $subscribe_result = subscribe($channel_id, $subscriber_id);
            //echo json_encode("you have subscribed successfully");
            echo json_encode(array("message" => "2"));
        } elseif(empty($subscribe_status)) {
            //echo json_encode("you need to loggin to subscribe");
            echo json_encode(array("message" => "3"));
        }
    }
}

if($_POST["type"] == "unsubscribe") {
    $media_id = $_POST["media_id"];
    $channel_id_result = get_channel_id($media_id);
    $channel_id = get_row($channel_id_result)[0];
    $subscriber_id = $_POST["subscriber_id"];
    $result = unsubscribe($channel_id, $subscriber_id);
    echo json_encode(array("message" => "4"));
}

if($_POST["type"] == "get_users") {
    $name = $_POST["name"];
    $result = get_users($name);
    $result = get_array($result);
    echo json_encode(array($result));
}

if($_POST["type"] == "block_user_access") {
    $media_id = $_POST["media_id"];
    $user_to_block_id = $_POST["user_to_block"];
    $result = check_media_access($media_id, $user_to_block_id);
    $result = json_encode($result);

    if($result) {
        echo json_encode(array("message" => "2"));
    } else {
        $result = block_user_access($media_id, $user_to_block_id);
        $result = json_encode($result);
        if($result) echo json_encode(array("message" => "1"));
        else echo json_encode(array("message" => "0"));
    }
}

if($_POST["type"] == "unblock_user") {
    $media_id = $_POST["media_id"];
    $user_to_unblock_id = $_POST["user_to_unblock"];
    $result = unblock_user($media_id, $user_to_unblock_id);
    $result = json_encode($result);
    if($result) echo json_encode(array("message" => "1"));
    else echo json_encode(array("message" => "0"));
}

if($_POST["type"] == "add_to_playlist") {
    $media_id = $_POST["media_id"];
    $playlist_name = $_POST["name"];
    if(empty($playlist_name)) {
        echo json_encode(array("message" => "-1"));
    } else {
        $user_id = $_POST["user_id"];
        $result = create_playlist($user_id, $playlist_name);
        $result = get_inserted_id();
        $result = add_to_playlist($result, $media_id);
        $result = json_encode($result);
        if($result) echo json_encode(array("message" => "1"));
        else echo json_encode(array("message" => "0"));
    }
}

if($_POST["type"] == "save_to_playlist") {
    $media_id = $_POST["media_id"];
    $user_id = $_POST["user_id"];
    $playlist_name = $_POST["playlist_name"];
    $playlist_id = $_POST["playlist_id"];
    $result = check_if_already_in_playlist($playlist_id, $user_id, $playlist_name);
    if($result) {
        echo json_encode(array("message" => "2"));
    } else {
        $result = add_to_playlist($playlist_id, $media_id);
        $result = json_encode($result);
        if($result) echo json_encode(array("message" => "1"));
        else echo json_encode(array("message" => "0"));
    }
}

if($_POST["type"] == "remove_playlist") {
  $media_id = $_POST["media_id"];
  $playlist_id = $_POST["playlist_id"];
  $result = remove_from_playlist($playlist_id, $media_id);
  if($result) echo json_encode(array("message" => "1"));
  else echo json_encode(array("message" => "0"));
}

if($_POST["type"] == "add_to_favorite") {
    $media_id = $_POST["media_id"];
    $favorite_name = $_POST["name"];
    if(empty($favorite_name)) {
        echo json_encode(array("message" => "-1"));
    } else {
        $user_id = $_POST["user_id"];
        $result = create_favorite($user_id, $favorite_name);
        $result = get_inserted_id();
        $result = add_to_favorite($result, $media_id);
        $result = json_encode($result);
        if($result) echo json_encode(array("message" => "1"));
        else echo json_encode(array("message" => "0"));
    }
}

if($_POST["type"] == "save_to_favorite") {
    $media_id = $_POST["media_id"];
    $user_id = $_POST["user_id"];
    $favorite_name = $_POST["favorite_name"];
    $favorite_id = $_POST["favorite_id"];
    $result = check_if_already_in_favorite($favorite_id, $user_id, $favorite_name);
    if($result) {
        echo json_encode(array("message" => "2"));
    } else {
        $result = add_to_favorite($favorite_id, $media_id);
        $result = json_encode($result);
        if($result) echo json_encode(array("message" => "1"));
        else echo json_encode(array("message" => "0"));
    }
}

if($_POST["type"] == "remove_favorite") {
  $media_id = $_POST["media_id"];
  $favorite_id = $_POST["favorite_id"];
  $result = remove_from_favorite($favorite_id, $media_id);
  if($result) echo json_encode(array("message" => "1"));
  else echo json_encode(array("message" => "0"));
}

if($_POST["type"] == "add_comment") {
  if(!logged_in()){
    echo json_encode(array("message" => "-1"));
  } else {
    $media_id = $_POST["media_id"];
    $user_id = $_POST["user_id"];
    $comment = $_POST["comment"];
    $conv_id = get_media_conversation_id($media_id);
    $result = add_comment($user_id,$conv_id,$comment);
    $result = json_encode($result);
    if($result) echo json_encode(array("message" => "1"));
    else echo json_encode(array("message" => "0"));
  }
}

if($_POST["type"] == "delete_comment") {
  $write_time = $_POST["write_time"];
  $user_id = $_POST["user_id"];
  $result = delete_comment($user_id,$write_time);

  $result = json_encode($result);

  if($result) echo json_encode(array("message" => "1"));
  else echo json_encode(array("message" => "0"));
}
?>
