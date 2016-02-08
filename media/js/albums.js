$.extend(view, {
  topAlbum10: function (lower_limit, upper_limit) {  
    $.ajax({
      dataType:'json',
      data:{
        limit:10,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topAlbum10Loader').hide();
              $('#topAlbum10').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124'
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbum10Loader').hide();
          $('#topAlbum10').html('<?php echo ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topAlbum: function (lower_limit, upper_limit, vars) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/album/get',
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:vars.template,
            data:{
              hide:vars.hide,
              json_data:data,
              rank:11
            },
            success: function (data) {
              $(vars.container + 'Loader').hide();
              $(vars.container).html(data);
            }
          });
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?php echo ERR_NO_DATA?>');
        }
      }
    });
  },
  topAlbumYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist' + year + 'Loader"/><table id="topArtist' + year + '" class="side_table"></table><div class="more"><a href="/album/' + year + '" title="Browse more">More <span class="number">' + year + '</span></</a></div></div><div class="container"><hr /></div>').appendTo($('#years'));
      vars = {
        container:'#topArtist' + year,
        limit:'0, 5',
        template:'/ajax/sideTable',
        hide:{
          artist:true,
          calendar:true,
          date:true
        }
      }
      view.topAlbum(year + '-00-00', year + '-12-31', vars);
    }
  }
});

$(document).ready(function () {
  view.topAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
  vars = {
    container:'#topAlbum',
    limit:'10, 200',
    template:'/ajax/columnTable'
  }
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  view.topAlbumYearly();
});
