<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");

//required scripts
require_once(BASE_PATH . "php_scripts/helper_functions.php");
require_once(BASE_PATH . "php_scripts/test_user_input.php");
require_once(BASE_PATH . "php_scripts/database_queries.php");

//starting code
if (!logged_in()) {
  header("Location:../login.php");
}

$ongrouppage = 1;

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$userid = get_user($email)[0];
$letter = $firstName[0];

$title = "Groups";
$groupID = -1;
$convID = -1;
$showGroups = 0;
$showDiscussions = 1;
$showConversations = 2;
$showMode = $showGroups;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST["groupname"])) {
    $groupID = $_POST["groupid"];
    $title = $_POST["groupname"];
    $showMode = $showDiscussions;
    if (!empty($_POST["newdiscussname"])) {
      $newDiscussName = $_POST["newdiscussname"];
      create_group_conversation($groupID, $newDiscussName);
    } else if(!empty($_POST['newuserinfo'])){
      $newUser = $_POST['newuserinfo'];
      $newUserID = get_user($newUser)[0];
      add_person_to_group($newUserID,$groupID);
    }
  } else if (!empty($_POST["convname"])) {
    $convID = $_POST["convid"];
    $title = $_POST["convname"];
    $showMode = $showConversations;
    if (!empty($_POST["comment"])) {
      $newComment = $_POST["comment"];
      add_comment($userid, $convID, $newComment);
      $_SESSION['convid'] = $convID;
      $_SESSION['convname'] = $title;
      unset($_POST);
      header("Location: ".$_SERVER['PHP_SELF']);
      exit;
    }
  } else if (!empty($_POST["newgroupname"])) {
    $newGroupName = $_POST["newgroupname"];
    create_group($userid, $newGroupName);
  }
} else if (isset($_SESSION['convid'])) {
      $showMode = $showConversations;
      $convID = $_SESSION["convid"];
      $title = $_SESSION['convname'];
  }
?>

<?php require_once(BASE_PATH . "basepagetop.php"); ?>
<div class="content">
  <!--PAGE DYNAMIC CONTENT-->
  <div class="user-contacts-wrapper">
    <div class="user-contacts">
      <?php
      if ($showMode == $showDiscussions) {
        echo ('
          <button class="open-button-add-person" onclick="openUserForm()">
            Add Member
          </button>
        ');
      }
      ?>
      <div class="user-contacts-header">
        <div class="user-contacts-item">
          <span><?php echo $title; ?></span>
        </div>
      </div>
      <div class="display-contacts-wrapper">
        <div class="contact-list-wrapper">
          <?php
          if ($showMode == $showGroups) {
            $groups = get_groups($userid);
            $groupName = "";
            while ($group = get_row($groups)) {
              $groupName = $group[0];
              $groupID = $group[1];
              require(BASE_PATH . "groupcard.php");
            }
          } else if ($showMode == $showDiscussions) {
            $dicucussions = get_group_conversations($groupID);
            $convName = "";
            $convID = -1;
            while ($dicucussion = get_row($dicucussions)) {
              $convID = $dicucussion[0];
              $convName = $dicucussion[1];
              require(BASE_PATH . "discussioncard.php");
            }
          } else if ($showMode == $showConversations) {
            $convComments = get_conversations($convID, 'ASC');
            require(BASE_PATH . "allcomments.php");
            require_once(BASE_PATH . 'add_discuss_comment.php');
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <button class="open-button" onclick="openForm()" style="display:<?php echo $showMode == $showConversations ? 'none' : 'block'; ?>">
    <?php if ($showMode == $showGroups) echo 'Create Group';
    else echo 'Create Discussion'; ?>
  </button>

  <div class="form-popup" id="myForm">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-container" method="post">
      <h1><?php if ($showMode == $showGroups) echo 'New Group';
          else echo 'New Discussion'; ?></h1>

      <label for="newgroupname">
        <b>
          <?php if ($showMode == $showGroups) echo 'Group Name';
          else echo 'Discussion Name'; ?>
        </b>
      </label>
      <input type="text" placeholder="Enter Name" name="<?php if ($showMode == $showGroups) echo 'newgroupname';
                                                        else echo 'newdiscussname'; ?>" required>
      <button type="submit" class="btn">Create</button>
      <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
      <?php
      if ($showMode == $showDiscussions) {
        echo ('<input type="hidden" name="groupid" value="' . $groupID . '">');
        echo ('<input type="hidden" name="groupname" value="' . $title . '">');
      }
      ?>
    </form>
  </div>
  <?php
  if ($showMode == $showDiscussions) {
    require(BASE_PATH . 'adduserform.php');
  }
  ?>
</div>
<?php require_once(BASE_PATH . "basepagebottom.php"); ?>
