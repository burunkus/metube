<div class="comment-wrapper">
  <div class="commenter-avatar-wrapper">
    <div class="commenter-avatar">
      <span><?php display_info($letter); ?></span>
    </div>
  </div>
  <form class="post-comment comment-inner-wrapper" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="add-comment-wrapper">
      <input type="text" name="comment" id="public-comment-text" class="public-comment" placeholder="Add a comment">
    </div>
    <div class="comment-btns-wrapper">
      <button class="btn btn-light" id="cancel-public-comment">CANCEL</button>
      <button type="submit" class="btn btn-primary" id="submit-public-comment">COMMENT</button>
      <input type="hidden" name="convid" value="<?php echo $convID; ?>">
      <input type="hidden" name="convname" value="<?php echo $title; ?>">
    </div>
  </form>
</div>
