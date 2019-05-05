<div class="all-comments-wrapper" id="all-comments-wrapper">
  <div class="comment-thread">
    <?php
    if(isset($message_send) && !isset($convComments)){

    }
    else{
      $commentSection = isset($convComments) ? $convComments : get_comment_section($media_id);
      if(logged_in()) {
        $viewerID = $_SESSION["user_id"];
      }
      $commentNumber=0;
      while ($row = get_array($commentSection)) {
        $personDName = $row['display_name'];
        $personComment = $row['comment'];
        $personfirstLetter = $personDName[0];
        $personID = $row['user_id'];
        $personWriteTime = $row['write_time'];
        echo ('
            <div class="comment" id="commentid'.$commentNumber.'">
              <div class="comment-body">
                <div class="author-avatar-wrapper">
                  <div class="author-avatar">
                    <span>' . $personfirstLetter . '</span>
                  </div>
                </div>
                <div class="author-comment-details">
                  <div class="author-name-wrapper">
                    <span>' . $personDName . '</span>
                    ');
                    if(logged_in()) {
                      if($personID == $viewerID ){
                      echo('<input type="hidden" value="'.BASE_PATH.'">');
                      echo('<input type="hidden" value="'.$viewerID.'">');
                      echo('<input type="hidden" value="'.$personWriteTime.'">');
                      echo('<button class="btn btn-link delete-comment-button" id=buttonid"'.$commentNumber.'">delete comment</button>');
                      }
                    }
                    echo('
                  </div>
                  <div class="author-comment-wrapper">
                    <span>' . $personComment . '</span>
                  </div>
                </div>
              </div>
            </div>
          ');
          $commentNumber++;
      }
    }
    ?>
  </div>
</div>
