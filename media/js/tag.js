$.extend(view, {
  getListeningHistory: function (type) {
    app.initChart();
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listener/get',
      data:{
        group_by:type + '(<?=TBL_listening?>.`date`)',
        limit:100,
        order_by:type + '(<?=TBL_listening?>.`date`) ASC',
        select:type + '(<?=TBL_listening?>.`date`) as `bar_date`',
        from:', <?=TBL_genres?>, <?=TBL_genre?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:(type == 'weekday') ? type + '(<?=TBL_listening?>.`date`) IS NOT NULL' : type + '(<?=TBL_listening?>.`date`) != \'00\''
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
  initTagEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getListeningHistory('month');
});