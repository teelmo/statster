<div class="heading_container"> 
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')">
        <?php
        if ($spotify_id !== FALSE) {
          ?>
          <a href="spotify:album:<?=$spotify_id?>" class="spotify_link"><div class="spotify_container album_spotify_container"></div></a>
          <?php
        }
        ?>
        <?=($most_listened_releaseyear !== false) ? '<span class="rank">#<span class="number">' . $most_listened_releaseyear . '</span></span>' : ''?>
        <span class="album_year number"><?=anchor(array('year', $year), $year, array('class' => 'album_year'))?></span>
      </div>
      <div class="top_info album_info">
        <h2><?=implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name']);}, $artists))?></h2>
        <h1><?=anchor(array('music', url_title($artist_name), url_title($album_name)), $album_name)?></h1>
      </div>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="full_container">
    <div class="container">
      <h2>Edit Album</h2>
      <?=form_open('', array('class' => '', 'id' => 'editAlbumForm'), array('editAlbum' => 'form'))?>
        <input type="hidden" name="album_id" value="<?=$album_id?>" />
        <input type="hidden" name="parent_artist_name" value="<?=isset($_GET['artist']) ? $_GET['artist'] : ''?>" />
        <input type="hidden" name="artist_names" value="<?=htmlentities(implode(',', array_map(function($artist) { return $artist['artist_name'];}, $artists)))?>" />
        <fieldset>
          <div class="input_container">
            <div class="label">Artist id</div>
            <div><input disabled="disabled" name="artist_id" type="text" value="<?=htmlentities(implode(', ', array_map(function($artist) { return $artist['artist_id'];}, $artists)))?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Artist name</div>
            <div><input type="text" class="artist_names" disabled="disabled" name="artist_names_disabled" value="<?=htmlentities(implode(', ', array_map(function($artist) { return $artist['artist_name'];}, $artists)))?>" autocomplete="off" /></div>
            <div id="artistAdd" class="hidden">
              <select data-placeholder="Select artist" class="chosen-select" multiple id="artists" name="artist_ids[]">
                <?=implode('', array_map(function($artist) use ($artists) {
                  return (in_array($artist['artist_name'], array_column($artists, 'artist_name'))) ? '<option selected="selected" value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . '</option>' : '<option value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . '</option>';
                }, $all_artists))?>
              </select>
            </div>
          </div>
          <div class="input_container">
            <div class="label">Album id</div>
            <div><input disabled="disabled" type="text" value="<?=htmlentities($album_id)?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Album name</div>
            <div><input type="text" name="album_name" value="<?=htmlentities($album_name)?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Release year</div>
            <div><input type="text" name="year" value="<?=$year?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Spotify id</div>
            <div><input type="text" name="spotify_id" class="spotify_id" value="<?=$spotify_id?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Cover art uri</div>
            <div><input type="text" name="image_uri" value="<?=$image_uri?>" /></div>
          </div>
        </fieldset>
        <div class="submit_container">
          <input type="submit" name="submit" value="Save album" />
          &nbsp;
          <?=anchor(array('music', url_title(isset($_GET['artist']) ? $_GET['artist'] : $artist_name), url_title($album_name)), 'Cancel')?>
        </div>
      </form>
    </div>
  </div>
