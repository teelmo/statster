<div class="main_container no_heading">
  <div class="left_container">
    <div class="container">
      <h1>Edit artist</h1>
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
          <?=anchor(array_map(function($item) { return url_title($item);}, explode('/', $_GET['redirect'])), 'Cancel')?>
        </div>
      </form>
    </div>
  </div>
