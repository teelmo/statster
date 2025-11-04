<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'artist_name' => $artist_name, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover artist_img img174" style="background-image:url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 174))?>')">
        <?php
        if ($spotify_id !== FALSE) {
          ?>
          <a href="spotify:artist:<?=$spotify_id?>" class="spotify_link"><div class="spotify_container artist_spotify_container"></div></a>
          <?php
        }
        ?>
      </div>
      <div class="top_info artist_info">
        <h1><?=anchor(array('music', url_title($artist_name)), $artist_name)?></h1>
      </div>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="full_container">
    <div class="container">
      <h2>Edit artist</h2>
      <?=form_open($_SERVER['REQUEST_URI'], array('class' => '', 'id' => 'editArtistForm'), array('editArtist' => 'form'))?>
        <input type="hidden" name="artist_id" value="<?=$artist_id?>" />
        <fieldset>
          <div class="input_container">
            <div class="label">Artist id</div>
            <div><input disabled="disabled" type="text" value="<?=htmlentities($artist_id)?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Artist name</div>
            <div><input type="text" name="artist_name" value="<?=htmlentities($artist_name)?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Associated artists</div>
            <div><input type="text" class="associated_artist_names" disabled="disabled" name="associated_artist_names_disabled" value="<?=htmlentities(implode(', ', array_map(function($artist) { return $artist['artist_name'];}, $associated_artists)))?>" autocomplete="off" /></div>
            <div id="associatedArtistAdd" class="hidden">
              <select data-placeholder="Select associated artist" class="chosen-select" multiple id="associated_artists" name="associated_artist_ids[]">
                <?=implode('', array_map(function($artist) use ($associated_artists) {
                  return (in_array($artist['artist_name'], array_column($associated_artists, 'artist_name'))) ? '<option selected="selected" value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . '</option>' : '<option value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . '</option>';
                }, $all_artists))?>
              </select>
            </div>
          </div>
          <div class="input_container">
            <div class="label">Spotify id</div>
            <div><input type="text" name="spotify_id" class="spotify_id" value="<?=$spotify_id?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Artist image uri</div>
            <div><input type="text" name="image_uri" value="<?=$image_uri?>" /></div>
          </div>
        </fieldset>
        <div class="submit_container">
          <input type="submit" name="submit" value="Save artist" />
          &nbsp;
          <?=anchor(array_map(function($item) { return url_title($item);}, explode('/', $_GET['redirect'])), 'Cancel')?>
        </div>
      </form>
    </div>
  </div>
