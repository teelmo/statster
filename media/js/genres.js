$.extend(view, {
  topGenre: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
              rank:1
            },
            success: function (data) {
              $(vars.container + 'Loader').hide();
              $(vars.container + '').html(data);
            },
            type:'POST',
            url:vars.template
          });
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?php echo ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  topGenreYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenre' + year + 'Loader"/><table id="topGenre' + year + '" class="side_table"></table></div><div class="container"><hr /></div>').appendTo($('#years'));
      var vars = {
        container:'#topGenre' + year,
        limit:3,
        size:32,
        template:'/ajax/sideTable',
        hide:{
          calendar:true,
          date:true,
          spotify:true
        }
      }
      view.topGenre(year + '-00-00', year + '-12-31', vars);
    }
  }
});

$(document).ready(function () {
  var vars = {
    container:'#topGenre',
    limit:'0, 200',
    template:'/ajax/columnTable'
  }
  view.topGenre('1970-00-00', '<?=CUR_DATE?>', vars);
  view.topGenreYearly();
});
