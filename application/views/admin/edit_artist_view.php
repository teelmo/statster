<div class="main_container no_heading">
  <div class="left_container">
    <div class="container">
      <h1>Edit artist</h1>
      <?=form_open('', array('class' => '', 'id' => 'editArtistForm'), array('editArtist' => 'form'))?>
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
            <div class="label">Spotify id</div>
            <div><input type="text" name="spotify_id" class="spotify_id" value="<?=$spotify_id?>" /></div>
          </div>
          <div class="input_container">
            <div class="label">Artist image uri</div>
            <div><input type="text" name="image_uri" value="<?=$image_uri?>" /></div>
          </div>
          </div>
        </fieldset>
        <div class="submit_container">
          <input type="submit" name="submit" value="Save artist" />
        </div>
      </form>
    </div>
  </div>
