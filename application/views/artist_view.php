<div id="leftCont">
  <div class="container">
    <h1><?=$artist_name?></h1>
  </div>
  <div class="container">
    <div class="tags">
      <?php
      foreach ($tags as $tag) {
        ?>
        <span class="tag <?=$tag['type']?>"><?=anchor(array('tag', $tag['type'], url_title($tag['name'])), $tag['name'])?></span>
        <?php
      }
      ?>
      <span class="tag moretags"><?=anchor(array(), '+')?></span>
    </div>
  </div>
  <div class="container">
    <div class="floatLeft">
      <img src="<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>" alt="" class="artistImg artistImg300" />
    </div>
    <div class="artistInfo">
      <table class="artistInfo">
        <tr>
          <th><?=$total_count?></th>
          <?php
          if (!empty($user_count)) {
            ?>
            <th><small><?=$user_count?></small></th>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td>listenings</td>
          <?php
          if (!empty($user_count)) {
            ?>
            <td>in your library</td>
            <?php
          }
          ?>
        </tr>
      </table>
      <h3 class="artistFan">Artist's fans</h3>
      <div>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistFanLoader"/>
        <ul id="artistFan" class="likeList noBullets"><!-- Content is loaded with AJAX --></ul>
      </div>
      <br />
      <div>
        <div>
          <div class="externalLink">
            <?=anchor('http://spotify', '<img src="' . site_url() . '/media/img/format_img/format_icons/spotify.png" alt="" class="icon" /> Search on Spotify')?>
          </div>
          <div class="externalLink">
            <?=anchor('http://lastfm', '<img src="' . site_url() . '/media/img/format_img/format_icons/lastfm.png" alt="" class="icon" /> Search on Last.fm')?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div class="container">
    <h2>Biography</h2>
    <p>
      Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed posuere interdum sem. Quisque ligula eros ullamcorper quis, lacinia quis facilisis sed sapien. Mauris varius diam vitae arcu. Sed arcu lectus auctor vitae, consectetuer et venenatis eget velit. Sed augue orci, lacinia eu tincidunt et eleifend nec lacus. Donec ultricies nisl ut felis, suspendisse potenti. Lorem ipsum ligula ut hendrerit mollis, ipsum erat vehicula risus, eu suscipit sem libero nec erat. Aliquam erat volutpat. Sed congue augue vitae neque. Nulla consectetuer porttitor pede. Fusce purus morbi tortor magna condimentum vel, placerat id blandit sit amet tortor.
    </p>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Similar artists</h2>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Artist's albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistAlbumLoader"/>
    <ul id="artistAlbum" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Shoutbox</h2>
  </div>
  <div class="container"><hr /></div>
</div>
<div id="rightCont">
  <div class="container">
    <h1>Latest listenings</h1>
  </div>
  <div class="container">
    <h1>Top listeners</h1>
  </div>
  <div class="container">
    <h1>Events</h1>
  </div>
</div>