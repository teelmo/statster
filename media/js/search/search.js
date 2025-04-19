$.extend(view, {
  getSearchResults: function () {
    $.ajax({
      data:{
        q:'<?=$q?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#searchResultLoader').hide();
              $('#searchResult').html(data);
            },
            type:'POST',
            url:'/ajax/searchList'
          });
        },
        204: function () { // 204 No Content
          $('#searchResultLoader').hide();
          $('#searchResult').html('');
        },
        400: function () {
          $('#searchResultLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/search/Get/100/'
    });
  },
  initSearchEvents: function () {
    
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.getSearchResults();
  view.initSearchEvents();
});