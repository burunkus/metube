<div class="comment-wrapper">
  <div class="commenter-avatar-wrapper">
    <div class="commenter-avatar">
      <!--<span><?php //display_info($letter); ?></span>-->
      <?php
        if($logged_in_flag) {
          echo '<span>'. $letter .'</span>';
        } else {
          echo '
            <span><i class="fas fa-user"></i></span>
          ';
        }
      ?>
    </div>
  </div>
  <div class="post-comment comment-inner-wrapper">
    <div class="add-comment-wrapper">
      <input type="text" name="public-comment-text" id="public-comment-text" class="public-comment" placeholder="Add a comment">
    </div>
    <div class="comment-btns-wrapper">
      <button class="btn btn-light" id="cancel-public-comment">CANCEL</button>
      <button class="btn btn-primary comment-button" id="submit-public-comment">COMMENT</button>
    </div>
</div>
</div>
<br>
