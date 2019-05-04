<?php
//starting php coded needed
session_start();
define("BASE_PATH", "../");

//required scripts
require_once(BASE_PATH."php_scripts/helper_functions.php");
require_once(BASE_PATH."php_scripts/test_user_input.php");
require_once(BASE_PATH."php_scripts/database_queries.php");

//starting code
if (!logged_in()) {
  header("Location:../login.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$userid = get_user($email)[0];
$letter = $firstName[0];

function get_contacts($userID1)
{
  global $userTable, $contactUser2, $contactTable, $contactUser1, $contactCategory;
  global $contactFriend, $userID, $userFirstName, $userLastName, $userEmail;
  global $userDisplayName, $contactContact;
  $con = "con";
  $query = "SELECT $userTable.$userFirstName, $userTable.$userLastName, $userTable.$userEmail , $userTable.user_name, " .
    "$userTable.$userDisplayName  FROM (SELECT $contactUser2 FROM $contactTable  WHERE $contactUser1 " .
    "= $userID1 AND ($contactCategory = $contactFriend OR $contactCategory = $contactContact)) AS $con INNER JOIN $userTable on " .
    "$con.$contactUser2 = $userTable.$userID;";
  $result = get_result($query);
  return $result;
}

function get_friends($userID1)
{
  global $userTable, $contactUser2, $contactTable, $contactUser1, $contactCategory;
  global $contactFriend, $userID, $userFirstName, $userLastName, $userEmail;
  global $userDisplayName;
  $con = "con";
  $query = "SELECT $userTable.$userFirstName, $userTable.$userLastName, $userTable.$userEmail , $userTable.user_name, " .
    "$userTable.$userDisplayName  FROM (SELECT $contactUser2 FROM $contactTable  WHERE $contactUser1 " .
    "= $userID1 AND $contactCategory = $contactFriend) AS $con INNER JOIN $userTable on " .
    "$con.$contactUser2 = $userTable.$userID;";
  $result = get_result($query);
  return $result;
}

function get_blocked($userID1)
{
  global $userTable, $contactUser2, $contactTable, $contactUser1, $contactCategory;
  global $contactBlocked, $userID, $userFirstName, $userLastName, $userEmail;
  global $userDisplayName;
  $con = "con";
  $query = "SELECT $userTable.$userFirstName, $userTable.$userLastName, $userTable.$userEmail , $userTable.user_name, " .
    "$userTable.$userDisplayName  FROM (SELECT $contactUser2 FROM $contactTable  WHERE $contactUser1 " .
    "= $userID1 AND $contactCategory = $contactBlocked) AS $con INNER JOIN $userTable on " .
    "$con.$contactUser2 = $userTable.$userID;";
  $result = get_result($query);
  return $result;
}

$contactMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST["add"])) {
    $personusername = $_POST["add"];
    update_contact($username, $personusername, $contactContact);
  }
  if (!empty($_POST["makefriend"])) {
    $personusername = $_POST["makefriend"];
    update_contact($username, $personusername, $contactFriend);
  }
  if (!empty($_POST["block"])) {
    $personusername = $_POST["block"];
    update_contact($username, $personusername, $contactBlocked);
  }
  if (!empty($_POST["remove"])) {
    $personusername = $_POST["remove"];
    update_contact($username, $personusername, $contactRemove);
  }
  if (!empty($_POST["removefriend"])) {
    $personusername = $_POST["removefriend"];
    update_contact($username, $personusername, $contactContact);
  }
  if (!empty($_POST["unblock"])) {
    $personusername = $_POST["unblock"];
    update_contact($username, $personusername, $contactRemove);
  }
}
?>

<?php require_once(BASE_PATH."basepagetop.php"); ?>
<div class="content">
  <!--PAGE DYNAMIC CONTENT-->
  <div class="message">
    <?php echo $contactMessage; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="close-message">
    <span aria-hidden="true">&times;</span>
  </div>
  <div class="user-contacts-wrapper">

    <div class="user-contacts">
      <div class="user-contacts-header">
        <div class="user-contacts-item">
          <span class="contact">Contacts</span>
        </div>
        <div class="user-contacts-item">
          <span class="friend">Friends</span>
        </div>
        <div class="user-contacts-item">
          <span class="blocked">Blocked</span>
        </div>
      </div>
      <div class="display-contacts-wrapper">
        <div class="contact-list-wrapper">
          <?php
          $people = get_contacts($userid);
          $personemail = $personusername = $personDisplayName = $personFirstName = $personLastName = "";
          $contactType = $contactContact;
          while ($person = get_row($people)) {
            $personFirstName = $person[0];
            $personLastName = $person[1];
            $personemail = $person[2];
            $personusername = $person[3];
            $personDisplayName = $person[4];
            $contactType = contact_type(get_user($username)[0], get_user($personusername)[0]);
            $personID = get_user($personemail)[0];
            include("../contactcard.php");

          }
          ?>
        </div>
        <div class="friends-list-wrapper">
          <?php
          $people = get_friends($userid);
          $personemail = $personusername = $personDisplayName = $personFirstName = $personLastName = "";
          $contactType = $contactContact;
          while ($person = get_row($people)) {
            $personFirstName = $person[0];
            $personLastName = $person[1];
            $personemail = $person[2];
            $personusername = $person[3];
            $personDisplayName = $person[4];
            $contactType = contact_type(get_user($username)[0], get_user($personusername)[0]);
            include("../contactcard.php");

          }
          ?>
        </div>
        <div class="blocked-list-wrapper">
          <?php
          $people = get_blocked($userid);
          $personemail = $personusername = $personDisplayName = $personFirstName = $personLastName = "";
          $contactType = $contactContact;
          while ($person = get_row($people)) {
            $personFirstName = $person[0];
            $personLastName = $person[1];
            $personemail = $person[2];
            $personusername = $person[3];
            $personDisplayName = $person[4];
            $contactType = contact_type(get_user($username)[0], get_user($personusername)[0]);
            include("../lookupcard.php");
          }
          ?>
        </div>
        <div class="add-new-contact">
          <a href="../people_search.php">
            <i class="fas fa-plus"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once(BASE_PATH."basepagebottom.php"); ?>
<script>
  let message = "<?php echo $contactMessage; ?>";
  if(message) {
    let messageDiv = document.querySelector(".message");
    if(messageDiv) {
      messageDiv.classList.add("show-block");
    }
    let closeMessageDiv = document.querySelector("#close-message");
    if(closeMessageDiv) {
      closeMessageDiv.addEventListener("click", () => {
        messageDiv.classList.remove("show-block");
      });
    }
  }
</script>
