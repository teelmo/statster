Object.assign(view, {
  // Init Edit album events.
  initEditArtistEvents: () => {
    var $select = $('#associatedArtistAdd select');
    $select.chosen({ search_contains: true });
    $select.prioritizedChosenSearch();
    document.querySelector('#associatedArtistAdd').style.display = '';
    document.querySelectorAll('.associated_artist_names').forEach(el => {
      el.style.display = 'none';
    });
    document.querySelectorAll('.spotify_id').forEach(el => {
      var clean = () => {
        el.value = el.value.replace(/(https:\/\/open\.spotify\.com\/artist\/)?([a-zA-Z0-9]{22})(.*)/, '$2');
      };
      el.addEventListener('blur', clean);
      el.addEventListener('keyup', clean);
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>`);
view.initEditArtistEvents();
