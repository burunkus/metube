<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/database_queries.php");
require_once("php_scripts/test_user_input.php");

//starting code
if(isset($_SESSION["firstname"])){
    $firstName = $_SESSION["firstname"];
}
if(isset($_SESSION["lastname"])) {
    $lastName = $_SESSION["lastname"];
}
if(isset($_SESSION["displayname"])) {
    $displayName = $_SESSION["displayname"];
}
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
}
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}
if(isset($firstName[0])) {
    $letter = $firstName[0];
}

$conn = get_conn();
$media_id = mysqli_real_escape_string($conn, $_GET["media_id"]);
$test_media_id = test_display_media_id($media_id);

$uploadedBy = $title = $description = $type = $extension = $path = $views = $allow_rating= $discussionType = $owner_display_name = $reviewsResult = $trackSubscriptionFlag = null;

$checkViewAccess = 0;

if (logged_in()) {
  $logged_in_flag = 1;
  $userId = $_SESSION["user_id"];
  if($test_media_id) {  //prevent adversarial attacks from the url
    $result = get_media($media_id, $userId, $logged_in_flag);
    if(mysqli_num_rows($result) == 0) {
      $checkViewAccess = 0; //no access
    } else {
      $checkViewAccess = 1; //access
      $result = get_array($result);
      $reviewsResult = get_reviews($media_id);
      $reviewsResult = get_row($reviewsResult);
      $reviewsResult = round($reviewsResult[0]);
      $uploadedBy = $result['uploader'];
      $title = $result['title'];
      $description = $result['media_description'];
      $type = $result['media_type'];
      $extension = $result['extension'];
      $path = $result['file_path'];
      $views = $result['view_count'];
      $owner_display_name = $result['display_name'];
      $discussionType = $result['discussion_type'];
      $allow_rating = $result['rating_type'];
      $numberOfSubscribersResult = get_number_of_subscribers($uploadedBy);
      $numberOfSubscribers = get_row($numberOfSubscribersResult)[0];

      $checkSubscribedToMedia = checkIfSubscribed($uploadedBy, $userId);
      if($checkSubscribedToMedia) {
        $trackSubscriptionFlag = 1;
      } else {
        $trackSubscriptionFlag = 0;
      }
      $users_playlist = get_playlist($userId);
      $users_favorite = get_favorite($userId);
    }
  }
} else {
    $userId = "";
    $logged_in_flag = 0;
    if($test_media_id) {
      $result = get_media($media_id, $userId, $logged_in_flag);
      $checkViewAccess = 1; //access since everyone can view public media
      $result = get_array($result);
      $reviewsResult = get_reviews($media_id);
      $reviewsResult = get_row($reviewsResult);
      $reviewsResult = round($reviewsResult[0]);
      $uploadedBy = $result['uploader'];
      $title = $result['title'];
      $description = $result['media_description'];
      $type = $result['media_type'];
      $extension = $result['extension'];
      $path = $result['file_path'];
      $views = $result['view_count'];
      $owner_display_name = $result['display_name'];
      $discussionType = $result['discussion_type'];
      $allow_rating = $result['rating_type'];
      $numberOfSubscribersResult = get_number_of_subscribers($uploadedBy);
      $numberOfSubscribers = get_row($numberOfSubscribersResult)[0];
      $trackSubscriptionFlag = 0;
    }
  }
?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
    <?php
      if($test_media_id) {
        if($checkViewAccess) {
          require_once("display_media_html.php");
        } else {
          $reviewsResult = null;
          $trackSubscriptionFlag = null;
          echo '
            <div class="has-no-media-access-container">
                <div class="has-no-media-access">
                    <span>You are blocked from viewing this media</span>
                </div>
            </div>
          ';
        }
      } else {  //mitigate possible attack
        echo '
          <div class="has-no-media-access-container">
            <div class="has-no-media-access">
                <span>Media does not exist</span>
            </div>
          </div>
        ';
      }
    ?>
</div>

<?php require_once("basepagebottom.php"); ?>
<script>
  let mediaID = "<?php echo $media_id; ?>";
  mediaID = parseInt(mediaID);
  let logged_in_status = "<?php echo $logged_in_flag; ?>";
  logged_in_status = parseInt(logged_in_status);
  let checkViewAccess = "<?php echo $checkViewAccess; ?>";
  checkViewAccess = parseInt(checkViewAccess);
  let allow_rating = "<?php echo $allow_rating; ?>";
  allow_rating = parseInt(allow_rating);

  if(!logged_in_status) {
    increaseViewCount(mediaID);
  } else if(logged_in_status && checkViewAccess) {
    increaseViewCount(mediaID);
  }

  let userID = "<?php echo $userId; ?>";
  userID = parseInt(userID);

  let avgReview = "<?php echo $reviewsResult; ?>";
  if(avgReview) avgReview = parseInt(avgReview);
  else avgReview = "";

  //color stars orange
  let stars = document.querySelectorAll(".star");
  if(stars) {
    for(let i = 0; i < avgReview; i++) {
        stars[i].classList.add("star-checked");
    }
  }

  //can only rate when logged in and if the uploader has allowed rating
  let ratingDiv = document.querySelector(".five-star-wrapper");
  if(ratingDiv) {
    ratingDiv.addEventListener("click", function(event) {
        let rate_value = event.target.dataset.star;
        rate_value = parseInt(rate_value);
        $.post("templates.php", {type: "updateReview", media_id: mediaID, rate_value: rate_value, user_id: userID, allow_rating: allow_rating}, function(response) {
            if(response.message == -1) {
                alert("you must be logged in to review");
            } else if(response.message == -2) {
              alert("rating is not enabled for this media");
            }
            else if(response.message == 5) {
                alert("updated review");
            } else if(response.message == 6) {
                alert("review added");
            }
        }, "json");
    });
  }

  let subscribeText = document.querySelector("#active-media-subscribe");
  let subscribeBtn = document.querySelector("#active-media-subscribe-btn");
  if(logged_in_status) {
    let flag = "<?php echo $trackSubscriptionFlag; ?>";
    flag = parseInt(flag);
    if(flag) {
        if(subscribeText) subscribeText.innerHTML = "UNSUBSCRIBE";
        if(subscribeBtn) subscribeBtn.setAttribute("data-subscribe-status", "unsubscribe");
    } else {
        if(subscribeText) subscribeText.innerHTML = "SUBSCRIBE";
        if(subscribeBtn) subscribeBtn.setAttribute("data-subscribe-status", "subscribe");
    }
  } else {
    if(subscribeText) subscribeText.innerHTML = "SUBSCRIBE";
  }

  if(subscribeBtn) {
    subscribeBtn.addEventListener("click", function(event) {
        let status = subscribeBtn.dataset.subscribeStatus;
        $.post("templates.php", {type: "subscribe", media_id: mediaID, subscriber_id: userID, status: status}, function(response) {
            if(response.message == 1) {
                let myConfirm = confirm("do you want to unsubscribe from this channel?");
                if(myConfirm) {
                    $.post("templates.php", {type: "unsubscribe", subscriber_id: userID, media_id: mediaID}, function(res) {
                        if(res.message == 4) {
                            alert("you have unsubscribed");
                            subscribeText.innerHTML = "SUBSCRIBE";
                            subscribeBtn.setAttribute("data-subscribe-status", "subscribe");
                            //update count
                            let subscriberCountElement = document.querySelector("#active-media-subscriber-count");
                            let currentSubscriberCount = parseInt(subscriberCountElement.innerHTML);
                            subscriberCountElement.innerHTML = currentSubscriberCount - 1;
                        }
                    }, "json");
                }
            } else if(response.message == 2) {
                alert("you have subscribed successfully!");
                subscribeText.innerHTML = "UNSUBSCRIBE";
                subscribeBtn.setAttribute("data-subscribe-status", "unsubscribe");

                //update count
                let subscriberCountElement = document.querySelector("#active-media-subscriber-count");
                let currentSubscriberCount = parseInt(subscriberCountElement.innerHTML);
                subscriberCountElement.innerHTML = currentSubscriberCount + 1;

            } else if(response.message == 3 || response.message == 0) {
                alert("you need to log in to subscribe");
            }
        }, "json");
    });
  }
    let playlistContainer = document.querySelector(".create-playlist-wrapper");
    let playlistBtn = document.querySelector("#playlist");
    if(playlistBtn) {
      playlistBtn.addEventListener("click", (event) => {
        if(logged_in_status) {
          //open Modal
          playlistContainer.classList.add("show-block");
        } else {
          alert("log in to add to playlist");
        }
      });
    }

    //close Modal
    let cancelBtn = document.querySelector(".cancel-playlist");
    if(cancelBtn) {
      cancelBtn.addEventListener("click", () => playlistContainer.classList.remove("show-block"));
    }

    //save media to a playlist
    let save_to_playlist = function(event) {
      let playlist_name = event.target.name;
      let playlist_id = event.target.value;
      var posting = $.post("templates.php", {type: "save_to_playlist", playlist_name: playlist_name, media_id: mediaID, playlist_id: playlist_id, user_id: userID});

      posting.done(function(response) {
        response = JSON.parse(response);
        if(response.message == 1) {
          alert("added to", playlist_name);
        } else if(response.message == 2) {
          alert("media already in playlist");
        } else if(response.message == 0) {
          alert("something went wrong");
        }
      });
    };

    //get the users playlists if any
    let savedPlayList = document.querySelectorAll(".playlist_check");
    if(savedPlayList) {
      for(let i = 0; i < savedPlayList.length; i++){
        savedPlayList[i].addEventListener("change", save_to_playlist);
      }
    }

    //create new playlist
    let playlistForm = document.querySelector("#add_to_playlist");
    if(playlistForm) {
      playlistForm.addEventListener("submit", (event) => {
        event.preventDefault();
        let playlistContainer = document.querySelector(".create-playlist-wrapper");
        let inputValue = document.querySelector("#playlistname").value;

        var posting = $.post("templates.php", {type: "add_to_playlist", name: inputValue, media_id: mediaID, user_id: userID});

        posting.done(function(response) {
          response = JSON.parse(response);
          if(response.message == 1) {
            playlistContainer.classList.remove("show-block");
            document.querySelector("#playlistname").value = "";
            alert("playlist created and media added");
          } else if(response.message == 0) {
            playlistContainer.classList.remove("show-block");
            alert("something went wrong");
          } else if(response.message == -1) {
            alert("playlist name can't be empty");
          }
        });
      });
    }

    //save to favorite list
    let save_to_favorite = function(event) {
      let favorite_name = event.target.name;
      let favorite_id = event.target.value;
      var posting = $.post("templates.php", {type: "save_to_favorite", favorite_name: favorite_name, media_id: mediaID, favorite_id: favorite_id, user_id: userID});

      posting.done(function(response) {
        response = JSON.parse(response);
        if(response.message == 1) {
          alert("added to", favorite_name);
        } else if(response.message == 0) {
          alert("something went wrong");
        } else if (response.message == 2) {
          alert("already in this favorite");
        }
      });
    };

    //get the users favourite list if any
    let savedFavorite = document.querySelectorAll(".favorite_check");
    if(savedFavorite) {
      for(let i = 0; i < savedFavorite.length; i++){
        savedFavorite[i].addEventListener("change", save_to_favorite);
      }
    }
</script>
