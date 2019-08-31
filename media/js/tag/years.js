$.extend(view, {
  getYearsHistory: function (lower_limit, type = '%Y') {
    view.getTopYears(lower_limit);
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:200,
        lower_limit:lower_limit,
        order_by:'<?=TBL_album?>.`year` ASC',
        select:'<?=TBL_album?>.`year` as bar_date',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
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
              $('.music_bar').show();
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type:'POST',
            url:'/ajax/musicBar'
          });
        },
        204: function () { // 204 No Content
          $('#historyLoader, .music_bar, #topYearLoader2').hide();
        },
        400: function () { // 400 Bad request
          $('#historyLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/year/get/'
    });
  },
  getTopYears: function (lower_limit, upper_limit = false, vars = false) {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      else {
        var date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit));
        lower_limit = date.toISOString().split('T')[0];
      }
      vars = {
        container:'#topYear',
        limit:'0, 10',
        template:'/ajax/columnTable'
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=!(empty($_GET['u'])) ? $_GET['u'] : ''?>'
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
              $(vars.container + 'Loader, ' + vars.container + 'Loader2').hide();
              $(vars.container + '').html(data);
            },
            type:'POST',
            url:vars.template
          });
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/year/get'
    });
  },
  getTopYearsYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYear' + year + 'Loader"/><table id="topYear' + year + '" class="side_table"></table></div><div class="container"><hr /></div>').appendTo($('#years'));
      var vars = {
        container:'#topYear' + year,
        limit:3,
        template:'/ajax/sideTable',
        hide:{
          calendar:true,
          date:true,
          size:32,
          spotify:true
        }
      }
      view.getTopYears(year + '-00-00', year + '-12-31', vars);
    }
  },
  getTopAlbumPerYear: function () {
    $.ajax({
      data:{
        username:'<?=!(empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbumYearlyLoader').hide();
              $('#topAlbumYearly').html(data);
            },
            type:'POST',
            url:'/ajax/albumList'
          });
        },
        204: function (data) { // 204 No Content
          // $(vars.container + 'Loader').hide();
          // $(vars.container).html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/year/get/yearly'
    });
  }
});

$(document).ready(function () {
  view.initChart();
  view.getYearsHistory('<?=$top_year_year?>', '%Y');
  view.getTopAlbumPerYear();
  view.getTopYearsYearly();
});