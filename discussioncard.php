<form class="someone" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="person-card-result">
      <div class="friend-container">
        <div class="friend-image-container">
          <div class="friend-image">
            <i class="fas fa-user"></i>
          </div>
        </div>
        <div class="friend-name">
          <p><?php echo $convName; ?></p>
          <br>
        </div>
      </div>
    <input type="hidden" name="convid" value="<?php echo $convID ; ?>">
    <input type="hidden" name="convname" value="<?php echo $convName; ?>">
    <input class="btn btn-link" type="submit" value="Go To Dicucussion">
  </div>
</form>
