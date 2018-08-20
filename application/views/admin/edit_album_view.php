<div id="mainCont" class="no_heading">
  <div id="leftCont">
    <div class="container">
      <h1>Edit Album</h1>
      <?=form_open('', array('class' => '', 'id' => 'editAlbumForm'), array('editAlbum' => 'form'))?>
        <input type="hidden" name="artist_id" value="<?=$artist_id?>" />
        <input type="hidden" name="album_id" value="<?=$album_id?>" />
        <fieldset>
          <div class="input_container">
            <div class="label">Artist</div>
            <div><input type="text" name="artist_name" disabled="disabled" value="<?=$artist_name?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Album name</div>
            <div><input type="text" name="album_name" value="<?=$album_name?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Release year</div>
            <div><input type="text" name="year" value="<?=$year?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Spotify uri</div>
            <div><input type="text" name="spotify_uri" value="<?=$spotify_uri?>" /></div>
          </div>
        </fieldset>
        <div class="submit_container">
          <input type="submit" name="submit" value="Save album" />
        </div>
      </form>
    </div>
  </div>
