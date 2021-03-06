<div class="main_container no_heading">
  <div class="left_container">
    <div class="container">
      <h1>Edit Album</h1>
      <?=form_open('', array('class' => '', 'id' => 'editAlbumForm'), array('editAlbum' => 'form'))?>
        <input type="hidden" name="album_id" value="<?=$album_id?>" />
        <fieldset>
          <div class="input_container">
            <div class="label">Artist name</div>
            <div><input type="text" name="artist_name" class="artist_name" value="<?=htmlentities($artist_name)?>" autocomplete="off" /></div>
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
            <div class="label">Spotify uri</div>
            <div><input type="text" name="spotify_uri" value="<?=$spotify_uri?>" /></div>
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
