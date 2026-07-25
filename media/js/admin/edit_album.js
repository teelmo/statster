Object.assign(view, {
  // Init Edit album events.
  initEditAlbumEvents: () => {
    initSearchableSelect(document.querySelector('#artistAdd select'));
    document.querySelector('#artistAdd').classList.remove('hidden');
    document.querySelectorAll('.artist_names').forEach(el => {
      el.style.display = 'none';
    });
    document.querySelectorAll('.spotify_id').forEach(el => {
      var clean = () => {
        el.value = el.value.replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2');
      };
      el.addEventListener('blur', clean);
      el.addEventListener('keyup', clean);
    });
  }
});

app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
view.initEditAlbumEvents();
