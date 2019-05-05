<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/database_queries.php");
require_once("php_scripts/table_values.php");

$media_keywords = $_GET["search"];

if (logged_in()) {
  $firstName = $_SESSION["firstname"];
  $lastName = $_SESSION["lastname"];
  $displayName = $_SESSION["displayname"];
  $email = $_SESSION["email"];
  $username = $_SESSION["username"];
  $letter = $firstName[0];
}

$keywordsRegex = "";
if (!empty($media_keywords)) {
  $keywords = explode(',', $media_keywords);
  $index = 0;
  $length = count($keywords);
  foreach ($keywords as $keyword) {
    $keyword = trim($keyword);
    $keywordsRegex .= $keyword;
    if (++$index <> $length) {
      $keywordsRegex .= '|';
    }
  }
}

if (logged_in()) {
  $user = get_user($username)[0];
  $result = get_search($keywordsRegex, $user, "isBlocked");
} else {
  $result = get_search($keywordsRegex);
}
?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <div id="most-viewed">
    <div class="multimedia-content-container">
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
            <div>
              <p>Type</p>
              <hr>
              <button class="type-button" data-filetype="video" onclick="hideNonFileTypes(this)">Video</button>
              <button class="type-button">Audio</button>
              <button class="type-button" data-filetype="image" onclick="hideNonFileTypes(this)">Image</button>
              <button class="type-button">Anime</button>
              <button class="type-button" data-filetype="all" onclick="hideNonFileTypes(this)">All</button>
            </div>
          </div>
        </div>
        <hr>
        <?php
        while ($row = get_array($result, MYSQLI_ASSOC)) {
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
                                      <span class="rating">rating: ' . $reviewsResult  . '</span>
                                      <input type="hidden" class="filetype" value="' . $type . '">
                                      <input type="hidden" class="categories" value="' . $mCatcategories . '">
                                  </div>
                              </div>
                              <div class="media-description">
                                  <p>' . $description . '</p>
                              </div>
                          </div>
                      </div>
                      </div>
                      ';
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php require_once("basepagebottom.php"); ?>
