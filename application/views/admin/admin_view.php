<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
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
      <h1>Admin</h1>
      <p>Unauthorized access not allowed.</p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Add genre</h2>
      <?=form_open('', array('class' => '', 'id' => 'addGenreForm'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="addGenreText" placeholder="New genre name" name="addGenreText" /></div>
        <div><input type="submit" name="addGenreSubmit" tabindex="10" id="addGenreSubmit" value="Add" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Add keywords</h2>
      <?=form_open('', array('class' => '', 'id' => 'addKeywordForm'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="addKeywordText" placeholder="New keyword name" name="addKeywordText" /></div>
        <div><input type="submit" name="addKeywordSubmit" tabindex="11" id="addKeywordSubmit" value="Add" /></div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Delete artist</h2>
      <?=form_open('', array('class' => '', 'id' => 'deleteArtistForm'))?>
        <div id="artistDelete">
          <select data-placeholder="Select artist" class="chosen-select" id="deleteArtist">
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
      <h2>Delete album</h2>
      <?=form_open('', array('class' => '', 'id' => 'deleteAlbumForm'))?>
        <div id="albumDelete">
          <select data-placeholder="Select album" class="chosen-select" id="deleteAlbum">
            <?=implode('', array_map(function($album) {
              return '<option value="' . $album['album_id'] . '">' . $album['artist_name'] . ' [' . $album['artist_id'] . '] – ' . $album['album_name'] . ' [' . $album['album_id'] . '], ' . $album['count'] . ' listenings</option>';
            }, $all_albums))?>
          </select>
        </div>
        <div><input type="submit" name="deleteAlbumSubmit" tabindex="13" id="deleteAlbumSubmit" value="Delete" /></div>
      </form>
    </div>
  </div>
