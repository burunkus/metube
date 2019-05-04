<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/test_user_input.php");
require_once("php_scripts/database_queries.php");

//starting code
if (!logged_in()) {
  header("Location:index.php");
  exit;
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$letter = $firstName[0];

require_once("php_scripts/process_upload.php");
process_upload();
?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <div class="alert alert-success" role="alert" id="upload-success-alert">
    Uploaded successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="close-upload-succ-alert">
    <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div id="upload-wrapper">
    <div class="upload-wrapper">
      <div class="container">
        <form id="uploadMedia" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group">
            <label for="media-type">Media Type</label>
            <select id="media-type" name="mediaType" class="form-control" form="uploadMedia">
              <option value=""></option>
              <option value="video">Video</option>
              <option value="audio">Audio</option>
              <option value="image">Image</option>
              <option value="anime">Anime</option>
            </select>
            <?php if(isset($media_type_error)) display_error($media_type_error); ?>
          </div>
          <div class="form-group">
            <label for="media-format">Format</label>
            <select id="media-format" name="mediaFormat" class="form-control">
              <option value=""></option>
            </select>
            <!--<span class="error">*</span>-->
            <?php if(isset($media_format_error)) display_error($media_format_error); ?>
          </div>
          <div class="form-group">
            <label for="file">Choose file (Max file size 30MB)</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="31457280"/><!--1M = 1048576-->
            <input id="file" type="file" name="file">
            <!--<span class="error">*</span>-->
            <?php if(isset($result)) display_error($result); ?>
          </div>
          <div class="form-group">
            <label for="media-title">Media Title</label>
            <input type="textarea" class="form-control" id="media-title" name="mediaTitle" placeholder="Add Title">
            <!--<span class="error">*</span>-->
            <?php if(isset($media_title_error)) display_error($media_title_error); ?>
          </div>
          <div class="form-group">
            <label>Media Description</label>
            <textarea class="form-control" name="mediaDescription" placeholder="Add Description"></textarea>
            <!--<span class="error">*</span>-->
            <?php if(isset($media_description_error)) display_error($media_description_error); ?>
          </div>
          <div class="form-group">
            <label for="mediaCategory">Category</label>
            <select id="mediaCategory" name="mediaCategory[]" class="form-control" form="uploadMedia" multiple>
              <option value="0">None</option>
              <option value="1">Sports</option>
              <option value="2">Video Games</option>
              <option value="3">Religon</option>
              <option value="4">Politics</option>
              <option value="5">Nature</option>
              <option value="6">Education</option>
              <option value="7">Drama</option>
              <option value="8">Science</option>
              <option value="9">Math</option>
              <option value="10">Art</option>
              <option value="11">Literature</option>
              <option value="12">Culture</option>
              <option value="13">Comedy</option>
              <option value="14">Music</option>
              <option value="15">Technology</option>
              <option value="16" selected>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label>Keywords</label>
            <textarea class="form-control" name="mediaKeywords" placeholder="Add Keywords (separated with a comma)"></textarea>
            <!--<span class="error">*</span>-->
            <?php if(isset($keyword_error)) display_error($keyword_error); ?>
          </div>
          <div class="form-group">
            <label for="view-access">Who can view this?</label>
            <select id="view-access" name="mediaAccess" class="form-control" form="uploadMedia">
              <option value=0 selected>Everyone</option>
              <option value=1>Friends</option>
              <option value=2>Private</option>
            </select>
          </div>
          <div class="form-group align-access-view">
            <label for="search-users">Block users from viewing this?</label>
            <button class="btn btn-link" id="search-to-block">Search users</button>
          </div>
          <div class="form-group">
            <label for="discussion-type">Enable discussions on this file?</label>
            <select id="discussion-type" name="mediaDiscussionType" class="form-control" form="uploadMedia">
              <option value=0 selected>Yes</option>
              <option value=1>No</option>
            </select>
          </div>
          <div class="form-group">
            <label for="enable-rate">Enable ratings on this file?</label>
            <select id="enable-rate" name="mediaRate" class="form-control" form="uploadMedia">
              <option value=0 selected>Yes</option>
              <option value=1>No</option>
            </select>
          </div>
          <input id="upload-btn" type="submit" name="submit" class="btn btn-primary" value="Upload">
          <button type="button" class="btn btn-secondary" name="cancel">Cancel</button>
        </form>
        <div id="block-a-user-container">
          <div id="block-a-user">
            <div class="block-a-user-header">
              <span>Search a MeTube User</span>
            </div>
            <form id="block-user-form" class="search-form" action="templates.php">
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
              <!--
              <div class="person">
                <div class="person-card-result">
                  <div class="friend-container">
                    <div class="friend-image-container">
                      <div class="friend-image">
                        <i class="fas fa-user"></i>
                      </div>
                    </div>
                    <div class="friend-name">
                      <p>Ebuka Okpala</p>
                    </div>
                  </div>
                  <button class="btn btn-link block-user-btn" type="button" name="" id="block-user-btn">block
                  </button>
                </div>
              </div>
              -->
            </div>
            <div class="people-search-btns-wrapper">
              <div class="people-search-btns">
                <button class="btn btn-secondary" id="cancel-block-user">Cancel</button>
                <button class="btn btn-primary" id="save-block-user">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once("basepagebottom.php"); ?>
<script>
  let success = "<?php echo $success; ?>";
  success = parseInt(success);
  if(success) {
    let successDiv = document.querySelector("#upload-success-alert");
    if(successDiv) {
      successDiv.classList.add("show-block");
    }
    let closeSuccDiv = document.querySelector("#close-upload-succ-alert");
    if(closeSuccDiv) {
      closeSuccDiv.addEventListener("click", (event) => {
        successDiv.classList.remove("show-block");
      });
    }
  }
</script>
