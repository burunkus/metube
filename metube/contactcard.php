<form class="someone" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="person-card-result">
    <a href="<?php echo BASE_PATH; ?>channel.php?channelid=<?php echo $personID ?>">
      <div class="friend-container">
        <div class="friend-image-container">
          <div class="friend-image">
            <i class="fas fa-user"></i>
          </div>
        </div>
        <div class="friend-name">
          <?php
          $hint = 'Display Name: ' . $personDisplayName . '&#10;' .
            'Name: ' . $personFirstName . ' ' . $personLastName . '&#10;' .
            'Email: ' . $personemail;
          ?>
          <p title="<?php echo $hint; ?>">
            <?php echo $personusername; ?></p>
          <br>
        </div>
      </div>
    </a>
    <button class="btn btn-link" type="submit" name="<?php set_contact_option($contactType) ?>" value="<?php echo $personusername; ?>">
      <?php set_contact_button_text($contactType) ?>
    </button>
    <button class="btn btn-link" type="submit" name="<?php set_friend_option($contactType) ?>" value="<?php echo $personusername; ?>">
      <?php set_friend_button_text($contactType) ?>
    </button>
    <button class="btn btn-link" type="submit" name="<?php set_block_option($contactType) ?>" value="<?php echo $personusername; ?>">
      <?php set_block_button_text($contactType) ?>
    </button>
  </div>
</form>
<form class="someone" action="<?php echo BASE_PATH . "mypage/messages.php"; ?>" method="post">
  <div class="person-card-result">
    <button class="btn btn-link" type="submit" name="sendmessage" value="<?php echo $personusername; ?>">
      Send Message
    </button>
  </div>
</form>
