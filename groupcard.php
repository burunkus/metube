<form class="someone" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="person-card-result">
    <div class="friend-container">
      <div class="friend-image-container">
        <div class="friend-image">
          <i class="fas fa-user"></i>
        </div>
      </div>
      <div class="friend-name">
        <p><?php echo $groupName; ?></p>
        <br>
      </div>
    </div>
    <input type="hidden" name="groupid" value="<?php echo $groupID; ?>">
    <input type="hidden" name="groupname" value="<?php echo $groupName; ?>">
    <input class="btn btn-link" type="submit" value="Go To Group">
  </div>
</form>
