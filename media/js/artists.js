$.extend(view, {
  topArtist10: function (lower_limit, upper_limit) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
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
            url:'/ajax/artistList/124',
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topArtist10Loader').hide();
              $('#topArtist10').html(data);
            }
          });
        }
      }
    });
  },
  topArtist: function (lower_limit, upper_limit, vars) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
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
              rank:11,
              size:32
            },
            success: function (data) {
              $(vars.container + 'Loader').hide();
              $(vars.container + '').html(data);
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
  topArtistYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2>' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist' + year + 'Loader"/><table id="topArtist' + year + '" class="side_table"></table><div class="more"><a href="/artist/' + year + '" title="Browse more">More ' + year + '</a></div></div><div class="container"><hr /></div>').appendTo($('#years'));
      vars = {
        container:'#topArtist' + year,
        limit:'0,5',
        template:'/ajax/sideTable',
        hide:{
          calendar:true,
          date:true
        }
      }
      view.topArtist(year + '-00-00', year + '-12-31', vars);
    }
  }
});

$(document).ready(function () {
  view.topArtist10('<?=$lower_limit?>', '<?=$upper_limit?>');
  vars = {
    container:'#topArtist',
    limit:'10, 200',
    template:'/ajax/columnTable'
  }
  view.topArtist('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  view.topArtistYearly();
});
