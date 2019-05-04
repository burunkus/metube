<div class="multimedia-content-container">
  <div class="multimedia-content media-theater">
    <div class="current-media-wrappper">
      <div class="active-media-section">
        <div class="active-media-content-wrapper">
          <div class="active-media-content">
            <div class="active-media">
              <?php
              if ($type == "video") {
                echo '
                    <video class="main-video" width="640" controls autoplay>
                    <source src=' . $path . ' type="video/' . $extension . '">
                    </video>
                    ';
              } elseif ($type == "image") {
                echo '
                    <img class="media-img" src=' . $path . ' alt=' . $title . '>
                    ';
              } elseif ($type == "anime") {
                echo '
                    <img class="media-img" src=' . $path . ' alt=' . $title . '>
                    ';
              } elseif ($type == "audio") {
                echo '
                    <audio class="main-audio" controls>
                    <source src=' . $path . ' type="audio/' . $extension . '">
                    Your browser does not support the audio element.
                    </audio>
                    ';
                }
              ?>
            </div>
          </div>
          <div class="active-media-meta-info">
            <div class="active-media-title">
                <span><?php echo $title; ?></span>
            </div>
            <div class="active-media-reviews">
                <div class="active-media-view-count">
                    <span><?php echo $views; ?> views</span>
                </div>
                <button class="btn btn-secondary" id="playlist">Save</button>
                <div class="viewer-review">
                    <div class="five-star-wrapper">
                        <div class="star">
                            <i class="far fa-star" data-star="1"></i>
                        </div>
                        <div class="star">
                            <i class="far fa-star" data-star="2"></i>
                        </div>
                        <div class="star">
                            <i class="far fa-star" data-star="3"></i>
                        </div>
                        <div class="star">
                            <i class="far fa-star" data-star="4"></i>
                        </div>
                        <div class="star">
                            <i class="far fa-star" data-star="5"></i>
                        </div>
                    </div>
                </div>
                <div class="user-rating-stats">
                    <span><?php echo $reviewsResult; ?></span>
                </div>
            </div>
           </div>

          <div class="active-media-description-wrapper">
            <div class="active-media-channel">
                <div class="active-media-channel-avatar-wrapper">
                    <div class="active-media-channel-avatar">
                        <a href="<?php echo BASE_PATH;?>channel.php?channelid=<?php echo $uploadedBy?>"></a>
                        <div class="active-media-channel-avatar-wrapper">
                            <div class="active-media-channel-avatar">
                                <span><?php echo $owner_display_name[0]; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="active-media-channel-info">
                    <div class="active-media-channel-title">
                        <span><?php echo $owner_display_name; ?></span>
                    </div>
                    <div class="active-media-description">
                        <span><?php echo $description; ?></span>
                    </div>
                </div>
            </div>
            <div class="subsribe-to-active-media-channel">
                <button type="button" class="btn btn-light" id="download">Download</button>
                <button type="button" id="active-media-subscribe-btn" class="btn btn-danger">
                    <span id="active-media-subscribe"></span>
                    <span id="active-media-subscriber-count"><?php echo $numberOfSubscribers; ?></span>
                </button>
            </div>
            <input type="hidden" id="linkpath" value="<?php echo $path;?>">
            <input type="hidden" id="filename" value="<?php echo $title . $extension;?>">
          </div>
        </div>
      </div>
      <div class="current-media-comments" id="current-media-comments">
        <input type="hidden" value="<?php echo $media_id?>" id="hiddenmediaid">
        <input type="hidden" value="<?php echo $userId?>" id="hiddenuserid">
          <?php
          if($discussionType == 0){
            require_once('comsecnewcomment.php');
            $commentSection = get_comment_section($media_id);
            require_once('allcomments.php');
          } else {
            echo "<p>comment section disabled</p>";
          }
          ?>
      </div>
      <div class="media-recommendation-section">
      </div>
  </div>
  <div class="secondary-media-recommendation-section">
    <?php
    $mediaResult = get_similar_media($media_id);
    while ($row = get_array($mediaResult, MYSQLI_ASSOC)) {
      $similar_media_id = $title  = $type;
      $thumbnailSrc = "";
      $similar_media_id = $row['media_id'];
      //echo($media_id);
      $title = $row['title'];
      $type = $row['media_type'];
      //display with our placeholders
      if ($type == "video") {
        $thumbnailSrc .= BASE_PATH."placeholders/video-image.jpg";
      } elseif ($type == "audio") {
        $thumbnailSrc .= BASE_PATH."placeholders/audio-image.png";
      } elseif ($type == "image") {
        $thumbnailSrc .= "https://via.placeholder.com/240x138.png?text=Image";
      } else {
        $thumbnailSrc .= "https://via.placeholder.com/240x138.png?text=Gif";
      }
      $url = BASE_PATH."display_media.php?media_id=$similar_media_id";
      echo '
        <div class="media-item-container">
        <div class="media-item">
            <div class="media-wrapper">
                <a id="thumbnail" href="' . $url . '">
                    <img src="' . $thumbnailSrc . '">
                </a>
            </div>
            <div class="text-wrapper">
                <div class="media-rank">
                    <div class="media-title-wrapper">
                        <h3 class="media-title">' . $title . '</h3>
                    </div>
                    <div class="metadata">
                        <input type="hidden" value="'. $similar_media_id . '">
                    </div>
                </div>
            </div>
        </div>
        </div>
      ';
    }
    ?>
  </div>
  <div class="create-playlist-wrapper">
    <div class="create-playlist">
      <?php
          if($users_playlist) {
              echo '<p>Save To Playlist...</p>';
              while($row = get_array($users_playlist)) {
                  $playlist_id = $playlist_name = null;
                  foreach ($row as $key => $value) {
                      if($key == "playlist_id") $playlist_id = $value;
                      else if ($key == "playlist_name") $playlist_name = $value;
                  }
                  echo '
                      <input type="checkbox" name="'. $playlist_name .'" value='. $playlist_id .' class="playlist_check">'. $playlist_name .'<br>
                  ';
              }
          }
          if($users_favorite) {
              echo '<p class="to-favorite">Save To Favorite...</p>';
              while($row = get_array($users_favorite)) {
                  $favorite_id = $favorite_name = null;
                  foreach ($row as $key => $value) {
                      if($key == "favorite_id") $favorite_id = $value;
                      else if ($key == "favorite_name") $favorite_name = $value;
                  }
                  echo '
                      <input type="checkbox" name="'. $favorite_name .'" value='. $favorite_id .' class="favorite_check">'. $favorite_name .'<br>
                  ';
              }
          }
      ?>
      <p class="create-list">Create playlist</p>
      <form action="templates.php" id="add_to_playlist" >
        Name:<br>
        <input type="text" name="playlistname" id="playlistname">
        <br><br>
        <input class="btn btn-secondary cancel-playlist" type="button" value="Cancel">
        <input class="btn btn-primary" type="submit" id="create-playlist" value="Create">
      </form>
    </div>
  </div>
</div>
