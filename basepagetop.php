<?php
if(!isset($onmessagepage)){
  unset($_SESSION['commentusername']);
}
if(!isset($ongrouppage)){
  unset($_SESSION['convid']);
  unset($_SESSION['convname']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MeTube</title>
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH; ?>bootstrap-4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH; ?>css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.7.1/js/all.js" integrity="sha384-eVEQC9zshBn0rFj4+TU78eNA19HMNigMviK/PU/FFjLXqa/GKPgX58rvt5Z8PLs7" crossorigin="anonymous"></script>
</head>

<body>
  <!--HEADER-->
  <div class="metube-header-wrapper">
    <div class="metube-header">
      <div id="mobile-search">
        <div id="close-search">
          <div class="close-search-icon-container">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="search">
          <form id="mobile-search-form" class="search-form" action="<?php echo BASE_PATH; ?>search.php">
            <div class="search-container">
              <input type="text" tabindex="0" name="search" id="mobile-search-input" class="search-input" placeholder="Search" value="<?php if (isset($media_keywords)) echo $media_keywords; ?>" autocomplete="off">
            </div>
            <button id="mobile-search-button" class="search-button" type="submit">
              <div class="search-icon">
                <i class="fas fa-search"></i>
              </div>
            </button>
          </form>
        </div>
      </div>
      <nav class="header-nav">
        <div class="hamburger-wrapper">
          <div class="hamburger">
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>
        <div class="logo">
          <h5>MeTube</h5>
        </div>
        <div class="search">
          <form id="search-form" class="search-form" action="<?php echo BASE_PATH; ?>search.php">
            <div class="search-container">
              <input type="text" tabindex="0" name="search" id="search-input" class="search-input" placeholder="Search" autocomplete="off" value="<?php if (isset($media_keywords)) echo $media_keywords; ?>">
            </div>
            <button id="search-button" class="search-button" type="submit">
              <div class="search-icon">
                <i class="fas fa-search"></i>
              </div>
            </button>
          </form>
        </div>
        <div class="list">
          <div class="mobile-search-icon-wrapper">
            <div class="mobile-search-icon">
              <i class="fas fa-search"></i>
            </div>
          </div>
          <div class="chat">
            <div class="chat-icon-wrapper">
              <div class="chat-icon">
                <i class="fas fa-comment fa-lg"></i>
              </div>
            </div>
          </div>
          <div class="metube-signin">
            <div class="nav-signin">
              <a href="<?php echo BASE_PATH; ?>login.php"></a>
              <button id="signin-btn">SIGN IN</button>
            </div>
          </div>
          <div class="signedin">
            <div class="signedin-active">
              <span class="echo-initial"><?php display_info($letter); ?></span>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <!--END OF HEADER-->

  <main>
    <!--SIDE BAR-->
    <div class="drawer-container">
      <nav class="drawer drawer-content">
        <div class="options">
          <div class="item">
            <a href="<?php echo BASE_PATH ?>index.php"></a>
            <span>Home</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>most_viewed.php"></a>
            <span>Most Viewed</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>recently_uploaded.php"></a>
            <span>Recently Uploaded</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>wordcloud.php"></a>
            <span>Word Cloud</span>
          </div>
        </div>
        <?php
        if (logged_in()) {
          echo ('
            <div id="upload" class="options">
            <div class="item">
              <a href="' . BASE_PATH . 'upload.php"></a>
              <span>Upload</span>
            </div>
            </div>
            ');
        } else {
          echo ('
            <div class="offline-message">
              <p>Sign in to like and upload medias, save to playlist, comment and subsscribe
              <a class="btn btn-link" href="' . BASE_PATH . 'login.php" role="button">SIGN IN</a></p>
            </div>
            ');
        }
        ?>
        <div class="options">
          <div class="item-title">
            <h3>Best of MeTube</h3>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>bestphotos.php"></a>
            <span>Photos</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>bestvideos.php"></a>
            <span>Videos</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>bestgifs.php"></a>
            <span>Gifs</span>
          </div>
          <div class="item">
            <a href="<?php echo BASE_PATH ?>bestaudios.php"></a>
            <span>Audios</span>
          </div>
        </div>
        <?php
        if (logged_in()) {
          require_once(BASE_PATH . 'php_scripts/database_queries.php');
          echo ('
              <div class="options">
                <div id="channels" class="item-title">
                  <h3>SUBSCRIPTIONS</h3>
              </div>');
          $subscriptions = get_subscriptions(get_user($email)[0]);
          while ($subscription = get_row($subscriptions)) {
            $url = BASE_PATH . "channel.php?channelid=" . $subscription[0];
            $dName = $subscription[1];
            echo ('
                <div class="item">
                  <a href="' . $url . '"></a>
                  <span>' . $dName . '</span>
                </div>
              ');
          }

          echo ('
              </div>
              <div class="options">
                  <div class="item">
                    <a href="' . BASE_PATH . 'mypage/settings.php"></a>
                    <span>Settings</span>
                  </div>
                  <div class="item">
                    <a href="' . BASE_PATH . 'people_search.php"></a>
                    <span>People Search</span>
                  </div>
              </div>
            ');
        }
        ?>
        <div id="drawer-footer">
          <div class="footer-content">
            <p>Database Management Systems Project. Spring 2019</p>
            <p>@MeTube 2019</p>

          </div>
        </div>
      </nav>
    </div>
    <!--END OF SIDE BAR-->

    <!--SIGNED IN--><!--boostrap-->
    <div class="signedin-wrapper">
      <div class="card">
        <div class="card-header">
          <div class="row no-wrap">
            <div class="col-xm-3">
              <div class="rounded-signin">
                <span class="echo-initial"><?php display_info($letter); ?></span>
              </div>
            </div>
            <div class="col-xm-9">
              <div class="row no-gutters">
                <div class="col-12">
                  <p id="account-name"><?php display_info($username); ?></p>
                </div>
                <div class="col-12">
                  <p id="account-email"><?php display_info($email); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="account-list">
            <div class="account-item-wrapper">
              <a href="<?php echo BASE_PATH; ?>channel.php" class="">
                <div class="account-item">
                  <p>My Channel</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="<?php echo BASE_PATH; ?>mypage/settings.php" class="">
                <div class="account-item">
                  <p>Settings</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="<?php echo BASE_PATH; ?>mypage/contacts.php" class="">
                <div class="account-item">
                  <p>Contacts</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="<?php echo BASE_PATH; ?>mypage/groups.php" class="">
                <div class="account-item">
                  <p>My Groups</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="<?php echo BASE_PATH; ?>logout.php" class="">
                <div class="account-item">
                  <p>Sign out</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--END OF SIGNED IN-->

    <!--Messages-->
    <div class="message-wrapper">
      <div class="card">
        <div class="card-header">
          <div class="row no-wrap">
            <div class="col-sm-6">
              <div id="message-title" class="message-title-wrapper">
                <p>Messages</p>
              </div>
              <div class="back-button">
                <div class="back-button-icon-wrapper">
                  <span class="back-button-icon">
                    <i class="fas fa-long-arrow-alt-left"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row align-items-center add-pad">
            <?php
              if(logged_in()){
                $messages = get_user_messages(get_user($email)[0]);
                if(mysqli_num_rows($messages) >= 1) {
                  while($message = get_row($messages)) {
                    $otherID = $message[0];
                    settype($otherID,'integer');
                    $otherConvID = $message[1];
                    $otherComment = $message[2];
                    $otherInfo = get_user($otherID);
                    $otherDName = $otherInfo[6];
                    $otherUserName = $otherInfo[4];
                    echo('
                      <form class="message-card" action="'.BASE_PATH.'mypage/messages.php" method="post">
                        <div class="friend-image-container">
                          <div class="friend-image">
                            <i class="fas fa-user"></i>
                          </div>
                        </div>
                        <div class="latest-message-container">
                          <p class="message-from">'.$otherDName.'</p>
                          <p class="latest-message">'.$otherComment.'</p>
                          <button type="submit" class="btn btn-link" name="convid" value="'.$otherConvID.'">
                            Open Messages
                          </button>
                        </div>
                      </form>
                    ');
                  }
                } else {
                  echo '
                    <div class="col-12 link-up">
                      <div class="row align-items-center">
                        <div class="col-12 no-friends">
                          <p>No Messages</p>
                        </div>
                        <div class="col-12 friendship-link">
                          <div class="link">
                            <a id="signin-to-chat" class="btn btn-primary btn-sm" href="' . BASE_PATH . 'contacts.php" role="button">Go to contacts</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  ';
                }
              } else {
                echo '
                  <div class="col-12 signin-when-not">
                    <div class="row align-items-center">
                      <div class="col-12 signin-to-chat">
                        <p>Signin to chat</p>
                      </div>
                      <div class="col-12 justify-center">
                        <a id="signin-to-chat" class="btn btn-primary btn-sm" href="' . BASE_PATH . 'login.php" role="button">SIGNIN</a>
                      </div>
                    </div>
                  </div>
                ';
              }
            ?>
            <!--
            <div class="col-12 link-up">
              <div class="row align-items-center">
                <div class="col-12 no-friends">
                  <p>No Friends Yet</p>
                </div>
                <div class="col-12 friendship-link">
                  <div class="link">
                    <p id="linkUrl">https://www.metube.com/ebuka-okpala</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 signin-when-not">
              <div class="row align-items-center">
                <div class="col-12 signin-to-chat">
                  <p>Signin to chat</p>
                </div>
                <div class="col-12 justify-center">
                  <a id="signin-to-chat" class="btn btn-light btn-sm" href="<?php //echo BASE_PATH; ?>login.html" role="button">SIGNIN</a>
                </div>
              </div>
            </div>
            -->
          </div>
          <!--
          <div class="friend-list">
            ECHO A USER'S FRIENDS HERE
            <div class="friend-container">
              <div class="friend-image-container">
                <div class="friend-image">
                  <i class="fas fa-user"></i>
                </div>
              </div>
              <div class="friend-name">
                <p>Ebuka Okpala</p>
              </div>
            </div>
          </div>
          -->
          <!--
          <div class="message-inbox">
            <div class="all-messages">
              PULL IN DYNAMICALLY
              <div class="message-card">
                <div class="friend-image-container">
                  <div class="friend-image">
                    <i class="fas fa-user"></i>
                  </div>
                </div>
                <div class="latest-message-container">
                  <p class="message-from">Ebuka Okpala</p>
                  <p class="latest-message">Hey, How are ya!</p>
                </div>
              </div>
            </div>
          </div>
          -->
          <!--
          <div class="conversation">
            PULL IN DYNAMICALLY
            <div class="your-chats">
              <div class="message">
                <div class="sender-name">
                  <p class="">Ebuka</p>
                </div>
                <div class="sender-message">
                  <p class="">Hey, How are you?</p>
                </div>
              </div>
              <div class="message">
                <div class="receiver-name">
                  <p class="">William</p>
                </div>
                <div class="receiver-message">
                  <p class="">I am fine. Thank you for asking</p>
                </div>
              </div>
              <div class="message">
                <div class="receiver-name">
                  <p class="">William</p>
                </div>
                <div class="receiver-message">
                  <p class="">I am fine. Thank you for asking</p>
                </div>
              </div>
              <div class="message">
                <div class="receiver-name">
                  <p class="">William</p>
                </div>
                <div class="receiver-message">
                  <p class="">I am fine. Thank you for asking</p>
                </div>
              </div>
            </div>
            <div class="send-message-wrapper">
              <form method="post" id="send-message-form">
                <input type="text" id="text" name="message" placeholder="Type a message">
                <button id="send-message">Send</button>
              </form>
            </div>
          </div>
          -->
        </div>
      </div>
    </div>
    <!--END OF MESSAGES-->
    <div id="content-container" class="content-container" style="margin-left: 240px;">
