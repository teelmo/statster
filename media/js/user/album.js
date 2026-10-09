Object.assign(view, {
  initOwnedAlbumFilters: () => {
    var formatFilter = document.querySelector('#ownedAlbumFormatFilter');
    var artistFilter = document.querySelector('#ownedAlbumArtistFilter');
    if (!formatFilter || !artistFilter) {
      return;
    }
    var applyFilters = () => {
      var selectedFormats = Array.from(formatFilter.selectedOptions).map(o => o.value);
      var artistQuery = artistFilter.value.trim().toLowerCase();
      document.querySelectorAll('.music_list li.block').forEach(block => {
        block.classList.add('hidden');
      });
      document.querySelectorAll('.music_list li.album').forEach(li => {
        var albumFormats = li.dataset.formats ? li.dataset.formats.split(',') : [];
        var matchesFormat = selectedFormats.length === 0 || albumFormats.some(id => selectedFormats.includes(id));
        var matchesArtist = artistQuery === '' || li.dataset.artist.includes(artistQuery);
        var visible = matchesFormat && matchesArtist;
        var block = li.previousElementSibling;
        li.classList.toggle('hidden', !visible);
        if (visible) {
          while (block && !block.classList.contains('block')) {
            block = block.previousElementSibling;
          }
          if (block) {
            block.classList.remove('hidden');
          }
        }
      });
    };
    formatFilter.addEventListener('change', applyFilters);
    artistFilter.addEventListener('input', applyFilters);
  },
  initSidebarEvents: () => {
    var shoutPromises = [view.getAlbumShouts().catch(() => {}), view.getArtistShouts().catch(() => {})];
    var likePromises = [view.recentlyFaned().catch(() => {}), view.recentlyLoved().catch(() => {})];

    Promise.all(shoutPromises).then(() => {
      var shoutRows = Array.from(document.querySelectorAll('.shouts tr'));
      if (shoutRows.length === 0) {
        document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
      } else {
        shoutRows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
        const shout = document.querySelector('#shout');
        shoutRows.forEach(row => {
          shout.appendChild(row);
        });
      }
      document.querySelector('#shoutLoader').classList.add('hidden');
    });

    Promise.all(likePromises).then(() => {
      var likeRows = Array.from(document.querySelectorAll('.likes tr'));
      if (likeRows.length === 0) {
        document.querySelector('#recentlyLiked').innerHTML = `<?=ERR_NO_RESULTS?>`;
      } else {
        likeRows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
        const recentlyLiked = document.querySelector('#recentlyLiked');
        likeRows.forEach(row => {
          recentlyLiked.appendChild(row);
        });
      }
      document.querySelector('#recentlyLikedLoader').classList.add('hidden');
    });

    view.getTopFormats('<?=$top_listening_format_profile?>').catch(() => {});
    view.getTopGenres('<?=$top_genre_profile?>').catch(() => {});
    view.getTopKeywords('<?=$top_keyword_profile?>').catch(() => {});
    view.getTopNationalities('<?=$top_nationality_profile?>').catch(() => {});
    view.getTopYears('<?=$top_year_profile?>').catch(() => {});
  }
});
app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
initSearchableSelect(document.querySelector('#ownedAlbumFormatFilter'));
view.initOwnedAlbumFilters();
view.initSidebarEvents();
