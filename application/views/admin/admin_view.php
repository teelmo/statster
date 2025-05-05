<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h2>Admin</h2>
      <p>Unauthorized access not allowed.</p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Clear cache</h3>
      <input type="submit" class="clear_cache" value="Clear cache" />
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Add artist</h3>
      <?=form_open('', array('class' => '', 'id' => 'addArtistForm'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="addArtistText" placeholder="New artist name" name="addArtistText" /></div>
        <div><input type="submit" name="addArtistSubmit" tabindex="10" id="addArtistSubmit" value="Add" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Add genre</h3>
      <?=form_open('', array('class' => '', 'id' => 'addGenreForm'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="addGenreText" placeholder="New genre name" name="addGenreText" /></div>
        <div><input type="submit" name="addGenreSubmit" tabindex="10" id="addGenreSubmit" value="Add" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Add keyword</h3>
      <?=form_open('', array('class' => '', 'id' => 'addKeywordForm'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="addKeywordText" placeholder="New keyword name" name="addKeywordText" /></div>
        <div><input type="submit" name="addKeywordSubmit" tabindex="11" id="addKeywordSubmit" value="Add" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Delete artist</h3>
      <?=form_open('', array('class' => '', 'id' => 'deleteArtistForm'))?>
        <div id="artistDelete">
          <select data-placeholder="Select artist" class="chosen-select" id="deleteArtist">
            <option>Select artist to delete</option>
            <?=implode('', array_map(function($artist) {
              return '<option value="' . $artist['artist_id'] . '">' . $artist['artist_name'] . ' [' . $artist['artist_id'] . '], ' . $artist['count'] . ' listenings</option>';
            }, $all_artists))?>
          </select>
        </div>
        <div><input type="submit" name="deleteArtistSubmit" tabindex="12" id="deleteArtistSubmit" value="Delete" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Delete album</h3>
      <?=form_open('', array('class' => '', 'id' => 'deleteAlbumForm'))?>
        <div id="albumDelete">
          <select data-placeholder="Select album" class="chosen-select" id="deleteAlbum">
            <option>Select album to delete</option>
            <?=implode('', array_map(function($album) {
              return '<option value="' . $album['album_id'] . '">' . $album['artist_name'] . ' [' . $album['artist_id'] . '] – ' . $album['album_name'] . ' [' . $album['album_id'] . '], ' . $album['count'] . ' listenings</option>';
            }, $all_albums))?>
          </select>
        </div>
        <div><input type="submit" name="deleteAlbumSubmit" tabindex="13" id="deleteAlbumSubmit" value="Delete" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Transfer album's data</h3>
      <?=form_open('', array('class' => '', 'id' => 'transferAlbumDataForm'))?>
        <div id="albumTransferData">
          <div>
            <select data-placeholder="Select source album" class="chosen-select" id="transferAlbumDataFrom">
              <option>Select album to transfer from</option>
              <?=implode('', array_map(function($album) {
                return '<option value="' . $album['album_id'] . '">' . $album['artist_name'] . ' [' . $album['artist_id'] . '] – ' . $album['album_name'] . ' [' . $album['album_id'] . '], ' . $album['count'] . ' listenings</option>';
              }, $all_albums))?>
            </select>
          </div>
          <br />
          <div>
            <select data-placeholder="Select target album" class="chosen-select" id="transferAlbumDataTo">
              <option>Select album to transfer to</option>
              <?=implode('', array_map(function($album) {
                return '<option value="' . $album['album_id'] . '">' . $album['artist_name'] . ' [' . $album['artist_id'] . '] – ' . $album['album_name'] . ' [' . $album['album_id'] . '], ' . $album['count'] . ' listenings</option>';
              }, $all_albums))?>
            </select>
          </div>
        </div>
        <div><input type="submit" name="transferAlbumDataSubmit" tabindex="13" id="transferAlbumDataSubmit" value="Transfer" /></div>
      </form>
    </div>
  </div>
