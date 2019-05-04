<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/test_user_input.php");
require_once("php_scripts/database_queries.php");
require_once("php_scripts/table_values.php");

//starting code
if (!logged_in()) {
  header("Location:login.php");
}

$firstName = $_SESSION["firstname"];
$lastName = $_SESSION["lastname"];
$displayName = $_SESSION["displayname"];
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$letter = $firstName[0];

$lookupname = "";
$contactMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (!empty($_GET["lookupname"])) {
    $lookupname = $_GET["lookupname"];
  }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST["lookupname"])) {
    $lookupname = $_POST["lookupname"];
  }
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

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <div class="people-search-container">
    <div class="people-search">
      <form id="" class="search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="search-container">
          <input type="text" tabindex="0" name="lookupname" id="search-input" class="search-input" placeholder="Search for someone" value="<?php echo $lookupname; ?>" autocomplete="off">
        </div>
        <button id="" class="search-button" type="submit">
          <div class="search-icon">
            <i class="fas fa-search"></i>
          </div>
        </button>
      </form>
    </div>
    <div class="message">
    <?php echo $contactMessage; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="close-message">
      <span aria-hidden="true">&times;</span>
    </div>
    <div class="people-result">
      <div class="person">
        <?php
        if ($lookupname != "") {
          $people = get_users($lookupname);
          $personemail = $personusername = $personDisplayName = $personFirstName = $personLastName = "";
          $contactType = 0;
          while ($person = get_row($people)) {
            $personID = $person[0];
            $personFirstName = $person[1];
            $personLastName = $person[2];
            $personemail = $person[3];
            $personusername = $person[4];
            $personDisplayName = $person[6];
            $contactType = contact_type(get_user($username)[0], get_user($personusername)[0]);
            if ($personusername != $username) {
              include("lookupcard.php");
            }
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php require_once("basepagebottom.php"); ?>
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
