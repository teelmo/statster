$.extend(view, {
  getYears: function (type) {
    view.initChart();
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/year/get/',
      data:{
        limit:200,
        lower_limit:'1970-00-00',
        order_by:'<?=TBL_album?>.`year` ASC',
        select:'<?=TBL_album?>.`year` as bar_date',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/barChart',
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
            }
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      }
    });
  },
  initYearEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getYears('%Y');
});