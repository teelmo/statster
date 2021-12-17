<div class="main_container no_heading">
  <div class="left_container">
    <div class="container">
      <h1>Edit Album</h1>
      <?=form_open('', array('class' => '', 'id' => 'editAlbumForm'), array('editAlbum' => 'form'))?>
        <input type="hidden" name="album_id" value="<?=$album_id?>" />
        <fieldset>
          <div class="input_container">
            <div class="label">Artist id</div>
            <div><input disabled="disabled" type="text" value="<?=htmlentities(implode(', ', array_map(function($artist) { return $artist['artist_id'];}, $artists)))?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Artist name</div>
            <div><input type="text" name="artist_name" class="artist_name" value="<?=htmlentities(implode(', ', array_map(function($artist) { return $artist['artist_name'];}, $artists)))?>" autocomplete="off" /></div>
            <div id="artistAdd" class="hidden">
              <select class="chosen-select" multiple id="artists">
                <?=implode('', array_map(function($artist) { return '<option selected="selected" value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . '</option>';}, $artists))?>
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
        </div>
      </form>
    </div>
  </div>
