$.extend(view, {
  populateArtistMenu: function () {
    return $.ajax({
      data:{
        limit:10000,
        lower_limit:'1970-00-00',
        group_by:'<?=TBL_artist?>.`artist_name`',
        order_by:'<?=TBL_artist?>.`artist_name`'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.each(data, function (i, value) {
            $('<option>' + value.artist_name + '</option>').appendTo($('#artists'));
          });
          $('#artistAdd select').chosen();
          $('#artistAdd').show();
          $('.artist_name').hide();
        }
      },
      url:'/api/artist/get',
      type:'GET'
    });
  },
  // Init Edit album events.
  initEditAlbumEvents: function () {
    $('.spotify_id').blur(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
    $('.spotify_id').keyup(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
  }
});

$(document).ready(function () {
  view.initEditAlbumEvents();
  view.populateArtistMenu();
});