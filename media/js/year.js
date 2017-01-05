$.extend(view, {
  topAlbum: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:10,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124'
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topArtist: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:10,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/artistList/124'
          });
        },
        204: function (data) { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/artist/get'
    });
  },
  topReleases: function () {
    $.ajax({
      data:{
        limit:10,
        lower_limit:'1970-00-00',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'<?=TBL_album?>.`year` = <?=$year?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topReleasesLoader').hide();
              $('#topReleases').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function (data) { // 204 No Content
          $('#topReleasesLoader').hide();
          $('#topReleases').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
});

$(document).ready(function () {
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topArtist('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topReleases('<?=$lower_limit?>', '<?=$upper_limit?>');
});
