$.extend(view, {
  topAlbum10: function (lower_limit, upper_limit) {  
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/album/get',
      data:{
        limit:10,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/albumList/124',
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topAlbum10Loader').hide();
              $('#topAlbum10').html(data);
            }
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbum10Loader').hide();
          $('#topAlbum10').html('<?php echo ERR_NO_DATA?>');
        }
      }
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
              rank:9,
              size:32
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
      $('<div class="container"><h2>' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist' + year + 'Loader"/><table id="topArtist' + year + '" class="sideTable"></table><div class="more"><a href="/album/' + year + '" title="Browse more">More from ' + year + '</</a></div></div><div class="container"><hr /></div>').appendTo($('#years'));
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
    limit:'8, 200',
    template:'/ajax/barTable'
  }
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  view.topAlbumYearly();
});
