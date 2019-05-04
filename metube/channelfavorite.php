<div class="multimedia-content-container playlist-bkg">
  <div class="multimedia-content">
    <div class="go-to-playlist"><a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=playlist<?php echo $suffix2;?>">go to your playlist</a></div>
    <div class="playlist-favorite">FAVORITES</div>
    <?php
      while($row = get_array($result)) {
        //print_r($row);
        $media_id = $favorite_id = $favorite_name = $title = $description = $type = $path = null;
        $thumbnailSrc = "";
        foreach ($row as $key => $value) {
            if($key == 'media_id') $media_id = $value;
            if($key == 'title') $title = $value;
            if($key == 'favorite_id') $favorite_id = $value;
            if($key == 'media_description') $description = $value;
            if($key == 'media_type') $type = $value;
        }
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

        $url = BASE_PATH."display_media.php?media_id=$media_id";
        echo '
        <div class="media-item-container">
        <div class="media-item">
            <div class="media-wrapper">
                <a id="thumbnail" href='. $url .'>
                    <img src='. $thumbnailSrc .'>
                </a>
            </div>
            <div class="text-wrapper">
                <div class="media-rank">
                    <div class="media-title-wrapper">
                        <h3 class="media-title">'. $title .'</h3>
                    </div>
                </div>
                <div class="media-description">
                    <p>'. $description .'</p>
                </div>
                <div class=remove-and-add>
                  <div class="remove-from-list">
                      <button class="btn btn-link remove-favorite" type="button" name='.$media_id.' value='.$favorite_id.'>Remove</button>
                  </div>
                  <div class="add-to-favorite">
                      <button class="btn btn-link add-favorite-btn" type="button" name='.$media_id.' value='.$favorite_id.'>Add to favorite</button>
                  </div>
                </div>
            </div>
        </div>
        </div>
        ';
      }
    ?>
  </div>
</div>
<div class="create-favorite-wrapper">
  <div class="create-favorite">
    <?php
      if($channelid == $myID) {
        if($users_favorite) {
            echo '<p class="to-favorite">Save To Favorite...</p>';
            while($row = get_array($users_favorite)) {
                $favorite_id = $favorite_name = null;
                foreach ($row as $key => $value) {
                    if($key == "favorite_id") $favorite_id = $value;
                    else if ($key == "favorite_name") $favorite_name = $value;
                }
                echo '
                    <input type="checkbox" name='. $favorite_name .' value='. $favorite_id .' class="favorite_check">'. $favorite_name .'<br>
                ';
            }
        }
        echo '
        <p class="create-list">Create favorite</p>
        <form action="templates.php" id="add_to_favorite" >
          Name:<br>
          <input type="text" name="favoritename" id="favoritename">
          <br><br>
          <input class="btn btn-secondary cancel-favorite" type="button" value="Cancel">
          <input class="btn btn-primary" type="submit" id="create-favorite" value="Create">
        </form>';
      }
    ?>
  </div>
</div>
<script>
    let userID = "<?php echo $myID; ?>";
    userID = parseInt(userID);
    let channelID = "<?php echo $channelid; ?>";
    channelID = parseInt(channelID);
    let logged_in_status = "<?php echo $logged_in_flag; ?>";
    logged_in_status = parseInt(logged_in_status);
    if(logged_in_status && channelID == userID) {
      window.onload = function() {
        handleFavorite(logged_in_status, userID);
      };
    }
</script>
