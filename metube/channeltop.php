<div class="channel-header-content-container">
  <div id="channel-header-content">
    <div id="channel-header">
      <div id="channel-avatar">
        <!--echo avatar here-->
        <img src="placeholder_person.jpg">
      </div>
      <div id="inner-channel-container">
        <h1 id="channel-title-container">
          <span id="channel-title"><?php echo $channelDisplayName; ?></span>
        </h1>
        <span id="subscriber-count"><?php echo $subscribeCount;?> subscribers</span>
      </div>
      <div id="subscribe-button">
        <button type="button" id="subscribe-unsubscribe-container" class="btn btn-secondary">
          <span id="subscribe">SUBSCRIBED</span>
          <span id="number_of_subscribers"><?php echo $subscribeCount;?></span>
        </button>
      </div>
    </div>
  </div>
  <div id="channel-navs-container">
    <div id="channel-navs">
      <div class="nav-scroll-icon-container nav-icon-left">
        <div class="nav-scroll-icon">
          <i class="fas fa-angle-left"></i>
        </div>
      </div>
      <div id="channel-navs-items-container">
        <div id="channel-navs-content">
          <div class="channel-navs-item">
            <span>HOME</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php<?php echo $suffix1;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>VIDEOS</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=video<?php echo $suffix2;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>AUDIOS</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=audio<?php echo $suffix2;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>IMAGES</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=images<?php echo $suffix2;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>ANIME</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=anime<?php echo $suffix2;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>PLAYLIST</span>
            <a href="<?php echo BASE_PATH . CHANNEL_PATH;?>channel.php?type=playlist<?php echo $suffix2;?>"></a>
          </div>
          <div class="channel-navs-item">
            <span>DISCUSSIONS</span>
            <a href=""></a>
          </div>
          <div class="channel-navs-item expandable"></div>
        </div>
      </div>
      <div class="nav-scroll-icon-container nav-icon-right">
        <div class="nav-scroll-icon">
          <i class="fas fa-angle-right"></i>
        </div>
      </div>
    </div>
  </div>
</div>
