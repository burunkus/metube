<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");

//required scripts
require_once(BASE_PATH . "php_scripts/helper_functions.php");
require_once(BASE_PATH . "php_scripts/database_queries.php");

//starting code
if (!logged_in()) {
  header("Location:../login.php");
}

$onmessagepage = 1;

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$userid = get_user($email)[0];
$letter = $firstName[0];

$title = "Messages";
$convID = -1;
$showGroups = 0;
$showConversations = 1;
$showMode = $showGroups;
$personID = "";
$personUserName = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $showMode = $showConversations;
  if(isset($_POST["sendmessage"])){
    $personUserName = $_POST["sendmessage"];
    $personInfo = get_user($personUserName);
    $personID = $personInfo[0];
  } else {
    $convID = $_POST["convid"];
    $personID = get_other_contact($userid, $convID);
    settype($personID,'integer');
    $personInfo = get_user($personID);
    $personUserName = $personInfo[4];
  }
  
  $title = "Messages with " . $personInfo[6];
 if (!empty($_POST["comment"])) {
    $newComment = $_POST["comment"];
    send_message($userid,$personID,$newComment);
  }
  $_SESSION['commentusername'] = $personUserName;
  unset($_POST);
  header("Location: ".$_SERVER['PHP_SELF']);
  exit;
} else if (isset($_SESSION['commentusername'])){
  $showMode = $showConversations;
  $personUserName = $_SESSION["commentusername"];
  $personInfo = get_user($personUserName);
  $personID = $personInfo[0];
  $title = "Messages with " . $personInfo[6];
}
?>

<?php require_once(BASE_PATH . "basepagetop.php"); ?>
<div class="content">
  <!--PAGE DYNAMIC CONTENT-->
  <div class="user-contacts-wrapper">
    <div class="user-contacts">
      <div class="user-contacts-header">
        <div class="user-contacts-item">
          <span><?php echo $title; ?></span>
        </div>
      </div>
      <div class="display-contacts-wrapper">
        <div class="contact-list-wrapper">
          <?php
          if ($showMode == $showGroups) {
            $messages = get_user_messages(get_user($email)[0]);
            while ($message = get_row($messages)) {
              $otherID = $message[0];
              settype($otherID, 'integer');
              $otherConvID = $message[1];
              $otherComment = $message[2];
              $user = get_user($otherID);
              $otherDName = $user[6];
              $personUserName = $user[4];
              echo ('
                <div class="message-card">
                  <div class="friend-image-container">
                    <div class="friend-image">
                      <i class="fas fa-user"></i>
                    </div>
                  </div>
                  <div class="latest-message-container">
                    <p class="message-from">' . $otherDName . '</p>
                    <p class="latest-message">' . $otherComment . '</p>
                  </div>
                </div>
                <form class="someone" action="'.BASE_PATH.'mypage/messages.php" method="post">
                  <div class="person-card-result">
                    <button class="btn btn-link" type="submit" name="sendmessage" value="'.$personUserName.'">
                      Send Message
                    </button>
                  </div>
                </form>
              ');
            }
          } else if ($showMode == $showConversations) {
            $message_send = 1;
            $convComments = get_messages($userid, $personID);
            require(BASE_PATH . "allcomments.php");
            require_once(BASE_PATH . 'send_message.php');
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once(BASE_PATH . "basepagebottom.php"); ?>
