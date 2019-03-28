$.extend(view, {
  topAlbum10: function () {
    $.ajax({
      data:{
        limit:8,
        lower_limit:'1970-00-00',
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbum10Loader').hide();
              $('#topAlbum10').html(data);
            },
            type:'POST',
            url:'/ajax/albumList'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbum10Loader').hide();
          $('#topAlbum10').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topAlbum: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
              rank:vars.rank,
              size:32
            },
            success: function (data) {
              $(vars.container + 'Loader').hide();
              $(vars.container).html(data);
            },
            type:'POST',
            url:vars.template
          });
        },
        204: function () { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topAlbumYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="sideTopAlbum' + year + 'Loader"/><table id="sideTopAlbum' + year + '" class="side_table"></table><div class="more"><a href="/<?=$tag_type?>/<?=url_title($tag_name)?>/<?=$type?>/' + year + '" title="Browse more">More <span class="number">' + year + '</span></</a></div></div><div class="container"><hr /></div>').appendTo($('#sideTable'));
      var vars = {
        container:'#sideTopAlbum' + year,
        hide:{
          artist:true,
          calendar:true,
          date:true,
          spotify:true
        },
        limit:5,
        rank:1,
        template:'/ajax/sideTable'
      }
      view.topAlbum(year + '-00-00', year + '-12-31', vars);
    }
  },
  initTagAlbumEvents: function () {
    
  }
});

$(document).ready(function () {
  view.topAlbum10();
  var vars = {
    container:'#topAlbum',
    limit:'8, 200',
    rank:9,
    template:'/ajax/columnTable'
  }
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  view.topAlbumYearly();
});