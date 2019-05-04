<div class="multimedia-content-container channel">
  <div class="multimedia-content" id="mediacontent">
    <div class="dropdown">
      <button onclick="showFilterOptions()" class="dropbtn">Filter</button>
      <div id="myDropdown" class="filterbar dropdown-content">
        <div>
          <p>Sort By</p>
          <hr>
          <button onclick="sortListTitles()">Title</button>
          <button onclick="sortByViewCount()">View count</button>
          <button onclick="sortByFileSize()">File Size</button>
          <button onclick="sortByDate()">Upload Time</button>
          <button onclick="sortByRating()">Rating</button>
        </div>
        <div>
          <p>Category</p>
          <hr>
          <button class="category-button" data-number="1" onclick="hideNonCategories(this)">Sports</button>
          <button class="category-button" data-number="2" onclick="hideNonCategories(this)">Video Games</button>
          <button class="category-button" data-number="3" onclick="hideNonCategories(this)">Religon</button>
          <button class="category-button" data-number="4" onclick="hideNonCategories(this)">Politics</button>
          <button class="category-button" data-number="5" onclick="hideNonCategories(this)">Nature</button>
        </div>
        <div>
          <p></p>
          <p></p>
          <p></p>
          <button class="category-button" data-number="6" onclick="hideNonCategories(this)">Education</button>
          <button class="category-button" data-number="7" onclick="hideNonCategories(this)">Drama</button>
          <button class="category-button" data-number="8" onclick="hideNonCategories(this)">Science</button>
          <button class="category-button" data-number="9" onclick="hideNonCategories(this)">Math</button>
          <button class="category-button" data-number="10" onclick="hideNonCategories(this)">Art</button>
        </div>
        <div>
          <p></p>
          <p></p>
          <p></p>
          <button class="category-button" data-number="11" onclick="hideNonCategories(this)">Literature</button>
          <button class="category-button" data-number="12" onclick="hideNonCategories(this)">Culture</button>
          <button class="category-button" data-number="13" onclick="hideNonCategories(this)">Comedy</button>
          <button class="category-button" data-number="14" onclick="hideNonCategories(this)">Music</button>
          <button class="category-button" data-number="15" onclick="hideNonCategories(this)">Technology</button>
        </div>
        <div>
          <p></p>
          <p></p>
          <p></p>
          <button class="category-button" data-number="16" onclick="hideNonCategories(this)">Other</button>
          <button class="category-button" data-number="17" onclick="hideNonCategories(this)">All</button>
        </div>
      </div>
    </div>
    <div class="channel-uploads">
      <p><?php echo $mediaType; ?></p>
    </div>
    <?php
    while ($row = get_array($mediaResult, MYSQLI_ASSOC)) {
      $media_id = $title = $description = $type = $extension = $path = $view_count = $time = $data_diff = $fileSize = null;
      $thumbnailSrc = "";
      $media_id = $row['media_id'];
      $title = $row['title'];
      $description = $row['media_description'];
      $type = $row['media_type'];
      $extension = $row['extension'];
      $path = $row['file_path'];
      $view_count = $row['view_count'];
      $time = $row['upload_time'];
      $date_diff = $row['date_diff'];
      $fileSize = $row['file_size'];
      $reviewsResult = get_reviews($media_id);
      $reviewsResult = get_row($reviewsResult);
      $reviewsResult = round($reviewsResult[0]);
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
      $categories = get_categories($media_id);
      $mCatcategories = "";
      while ($category = get_row($categories)) {
        $mCatcategories .= $category[0] . "|";
      }
      $url = BASE_PATH."display_media.php?media_id=$media_id";
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
                        <span class="viewcount" id="media-metadata">' . $view_count . ' views |</span>
                        <span class="time">upload date: ' . extract_date($time) . ' |</span>
                        <span class="filesize">file size: ' . $fileSize . ' |</span>
                        <span class="rating">rating: ' . $reviewsResult . '</span>
                        <input type="hidden" class="filetype" value="' . $type . '">
                        <input type="hidden" class="categories" value="' . $mCatcategories . '">
                        <input type="hidden" value="'. $media_id . '">
                    </div>
                </div>
                <div class="media-description">
                    <p>' . $description . '</p>
                </div>';
                if($channelid == $myID){
                  echo('
                    <div class="manage-access">
                      <button class="btn btn-link manage-access-btn">Manage access</button>
                    </div>
                  ');
                }
            echo'
            </div>
        </div>
        </div>
        ';
    }
    ?>
    <div id="block-a-user-container">
      <div id="block-a-user">
        <div class="block-a-user-header">
          <span>Search a MeTube User</span>
        </div>
        <form id="block-user-form" class="search-form" action="../templates.php">
          <div class="search-container">
            <input type="text" tabindex="0" name="lookupuser" id="block-user-search-input" class="search-input" placeholder="Search for someone" autocomplete="off">
          </div>
          <button id="block-user-search" class="search-button" type="submit">
            <div class="search-icon">
              <i class="fas fa-search"></i>
            </div>
          </button>
        </form>
        <div class="people-result">
        </div>
        <div class="people-search-btns-wrapper">
          <div class="people-search-btns">
            <button class="btn btn-secondary" id="cancel-block-user">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  let accessBtn = document.querySelectorAll(".manage-access-btn");
  if(accessBtn) {
    for(let button of accessBtn) {
      button.addEventListener("click", manageAccess);
    }
  }

  function manageAccess(event) {
    let media_id = event.target.parentNode.parentNode.firstElementChild.lastElementChild.lastElementChild.value;
    //open Modal
    let searchModal = document.querySelector("#block-a-user-container");
    searchModal.classList.add("show-block");

    //Cancel and Close Modal
    let cancelBtn = document.querySelector("#cancel-block-user");
    cancelBtn.addEventListener("click", () => {
      searchModal.classList.remove("show-block");
    });

    let containingDiv = document.querySelector(".people-result");
    let form =  document.querySelector("#block-user-form");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      let inputValue = document.querySelector("#block-user-search-input").value;
      let actionUrl = form.action;

      //post request using JQUERY
      var posting = $.post(actionUrl, {type: "get_users", name: inputValue});

      //put the result in the div
      posting.done(function(response) {
        response = JSON.parse(response);
        let id, firstName, lastName;
        for(const person of response) {
          for(let key in person) {
            if(key == "user_id") id = person[key];
            else if(key == "first_name") firstName = person[key];
            else if(key == "last_name") lastName = person[key];
            else continue;
          }
          //append each person to the page
          create_element(containingDiv, id, firstName, lastName);
          //add listener to the block button
          let block = document.querySelectorAll(".block-user-btn");
          if(block) {
            for(let i = 0; i < block.length; i++) {
              block[i].addEventListener("click", blockTheUser);
            }
          }
        }
      });
    });

    function blockTheUser(event) {
      let user_to_be_blocked_id = event.target.value;
      let posting = $.post("../templates.php", {type: "block_user_access", media_id: media_id, user_to_block: user_to_be_blocked_id});

      posting.done(function(response) {
        response = JSON.parse(response);
        if(response.message == 1) {
          alert("user blocked");
          event.target.disabled = true;
        } else if(response.message == 0) {
          alert("blocking failed");
        } else if(response.message == 2) {
          alert("you have already blocked this user from accessing this file");
        } else alert(response);

      });
    }
  }
</script>
