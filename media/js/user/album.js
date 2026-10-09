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
  }
});
initSearchableSelect(document.querySelector('#ownedAlbumFormatFilter'));
view.initOwnedAlbumFilters();
