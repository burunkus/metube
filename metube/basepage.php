<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MeTube</title>
  <link rel="stylesheet" type="text/css" href="/bootstrap-4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/css/style.css">
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
          <form id="mobile-search-form" class="search-form" action="/search.php">
            <div class="search-container">
              <input type="text" tabindex="0" name="search" id="mobile-search-input" class="search-input" placeholder="Search" autocomplete="off">
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
          <form id="search-form" class="search-form" action="/search.php">
            <div class="search-container">
              <input type="text" tabindex="0" name="search" id="search-input" class="search-input" placeholder="Search" autocomplete="off">
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
          <!--
                <div class="display-settings">
                    <div class="settings-icon-wrapper">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                -->
          <div class="metube-signin">
            <div class="nav-signin">
              <a href="login.php">SIGN IN</a>
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
            <a href="index.php"></a>
            <span>Home</span>
          </div>
          <div class="item">
            <a href="most-viewed.php"></a>
            <span>Most Viewed</span>
          </div>
          <div class="item">
            <a href="recently-viewed.php"></a>
            <span>Recently Uploaded</span>
          </div>
        </div>
        <div id="upload" class="options">
          <div class="item">
            <a href="javascript:void(0);"></a>
            <span>Upload</span>
          </div>
        </div>
        <div class="options">
          <div class="item-title">
            <h3>Best of MeTube</h3>
          </div>
          <div class="item">
            <a href=""></a>
            <span>Photos</span>
          </div>
          <div class="item">
            <a href=""></a>
            <span>Politics</span>
          </div>
          <div class="item">
            <a href=""></a>
            <span>Anime</span>
          </div>
          <div class="item">
            <a href=""></a>
            <span>Comedy</span>
          </div>
        </div>
        <div class="options">
          <div id="channels" class="item-title">
            <h3>SUBSCRIPTIONS</h3>
          </div>
          <!--dynamicall pull and put channels here
                <div class="item">
                    <a href=""></a>
                    <span>(channel name here)</span>
                </div>
                -->
        </div>
        <div class="options">
          <div class="item">
            <a href="/mypage/settings.php"></a>
            <span>Settings</span>
          </div>
        </div>
        <div id="drawer-footer">
          <div class="footer-content">
            <p>Database Management Systems Project. Spring 2019</p>
            <p>@MeTube 2019</p>

          </div>
        </div>
      </nav>
    </div>
    <!--END OF SIDE BAR-->

    <!--SIGNED IN-->
    <!--boostrap-->

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
                  <p id="account-name"><?php display_info($displayName); ?></p>
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
              <a href="#" class="">
                <div class="account-item">
                  <p>My Channel</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="/mypage/settings.php" class="">
                <div class="account-item">
                  <p>Settings</p>
                </div>
              </a>
            </div>
            <div class="account-item-wrapper">
              <a href="/logout.php" class="">
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
                <p class="friends">Friends</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="friends-btn-wrapper">
                <button id="friends-btn" class="friends">FRIENDS</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-12 have-nots">
              <div class="row align-items-center">
                <div class="col-12 no-friends">
                  <p>No Friends Yet</p>
                </div>
                <div class="col-12 justify-center">
                  <button type="button" id="add-friend" class="btn btn-primary">ADD FRIENDS</button>
                </div>
              </div>
            </div>
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
                  <a id="signin-to-chat" class="btn btn-light btn-sm" href="login.html" role="button">SIGNIN</a>
                </div>
              </div>
            </div>
          </div>
          <div class="friend-list">
            <!--ECHO A USER'S FRIENDS HERE-->
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
          <div class="message-inbox">
            <div class="all-messages">
              <!-- PULL IN DYNAMICALLY-->
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
          <div class="conversation">
            <!--PULL IN DYNAMICALLY-->
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
        </div>
      </div>
    </div>
    <!--END OF MESSAGES-->
    <div id="content-container" class="content-container" style="margin-left: 240px;">
      <div class="content">
        <!--PAGE DYNAMIC CONTENT-->

        <!--END OF PAGE DYNAMIC CONTENT-->
      </div>
    </div>
  </main>
</body>
<script type="text/javascript" src="/js/app.js"></script>
<script>
  <?php
    if (logged_in()) {
        echo "loggedin()";
    }
  ?>
</script>

</html>
