$.extend(view, {
  getYearsHistory: function (type) {
    view.initChart();
    $.ajax({
      data:{
        limit:200,
        lower_limit:'1970-00-00',
        order_by:'<?php echo TBL_album?>.`year` ASC',
        select:'<?php echo TBL_album?>.`year` as bar_date',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'<?php echo TBL_album?>.`year` <> 0'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:type
            },
            success: function (data) {
              $('#historyLoader').hide();
              $('#history').html(data).hide();
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type:'POST',
            url:'/ajax/musicBar'
          });
        },
        204: function () { // 204 No Content
          $('#historyLoader').hide();
          $('#history').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#historyLoader').hide();
          alert('<?php echo ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/year/get/'
    });
  },
  topYear: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        select:'<?php echo TBL_album?>.`year` as name',
        upper_limit:upper_limit,
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
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
      url:'/api/year/get'
    });
  },
  topYearYearly: function () {
    for (var year = <?php echo CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYear' + year + 'Loader"/><table id="topYear' + year + '" class="side_table"></table></div><div class="container"><hr /></div>').appendTo($('#years'));
      var vars = {
        container:'#topYear' + year,
        limit:3,
        template:'/ajax/sideTable',
        hide:{
          calendar:true,
          calendar:true,
          date:true,
          size:32,
          spotify:true
        }
      }
      view.topYear(year + '-00-00', year + '-12-31', vars);
    }
  },
  initYearEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getYearsHistory('%Y');
  view.topYearYearly();
});