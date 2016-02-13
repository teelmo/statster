$.extend(view, {
  getYearsHistory: function (type) {
    view.initChart();
    $.ajax({
      data:{
        limit:200,
        lower_limit:'1970-00-00',
        order_by:'<?=TBL_album?>.`year` ASC',
        select:'<?=TBL_album?>.`year` as bar_date',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:'<?=TBL_album?>.`year` <> 0'
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
              var categories = [];
              var data = [];
              $.each($('#history .time'), function (i, el) {
                categories.push($(this).html());
              });
              $.each($('#history .count'), function (i, el) {
                data.push(parseInt($(this).html()));
              });
              app.chart.xAxis[0].setCategories(categories, false);
              app.chart.series[0].setData(data, true);
            },
            type:'POST',
            url:'/ajax/barChart'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
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
        select:'<?=TBL_album?>.`year` as name',
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
      url:'/api/year/get'
    });
  },
  topYearYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYear' + year + 'Loader"/><table id="topYear' + year + '" class="side_table"></table></div><div class="container"><hr /></div>').appendTo($('#years'));
      var vars = {
        container:'#topYear' + year,
        limit:'0,5',
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