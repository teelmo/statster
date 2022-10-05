$.extend(view, {
  getTopAlbum10: function (lower_limit, upper_limit = false) {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      else {
        var date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>'
    }
    $.ajax({
      data:{
        limit:8,
        hide:{},
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topAlbum10Loader, #topAlbum10Loader2').hide();
              $('#topAlbum10').html(data);
            },
            type:'POST',
            url:'/ajax/albumList'
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbum10Loader, #topAlbum10Loader2').hide();
          $('#topAlbum10').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
    var vars = {
      container:'#topAlbum',
      limit:'8, 192',
      template:'/ajax/columnTable'
    }
    view.getTopAlbum(lower_limit, upper_limit, vars);
  },
  getTopAlbum: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
              rank:9,
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
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  dailyAlbumCount: function (limit, vars) {
    $.ajax({
      data:{
        limit:vars.limit,
        lower_limit:limit,
        upper_limit:limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $(vars.container + 'Loader').hide();
          $(vars.container).html(data);
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/album/get/count'
    });
  },
  getTopAlbumYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="sideTopAlbum' + year + 'Loader"/><table id="sideTopAlbum' + year + '" class="side_table"></table><div class="more"><a href="/album/' + year + '" title="Browse more">More <span class="number">' + year + '</span></</a></div></div><div class="container"><hr /></div>').appendTo($('#sideTable'));
      var vars = {
        container:'#sideTopAlbum' + year,
        hide:{
          artist:true,
          calendar:true,
          date:true,
          spotify:true
        },
        limit:5,
        template:'/ajax/sideTable'
      }
      view.getTopAlbum(year + '-00-00', year + '-12-31', vars);
    }
  },
  getTopAlbumMonthly: function (year) {
    for (var month = 1; month <= 12; month++) {
      var month_str = new Array();
      month_str[1] = 'January';
      month_str[2] = 'February';
      month_str[3] = 'March';
      month_str[4] = 'April';
      month_str[5] = 'May';
      month_str[6] = 'June';
      month_str[7] = 'July';
      month_str[8] = 'August';
      month_str[9] = 'September';
      month_str[10] = 'October';
      month_str[11] = 'November';
      month_str[12] = 'December';
      var str = '' + month;
      var pad = '00';
      var pad_month = pad.substring(0, pad.length - str.length) + str;
      $('<div class="container"><h2 class="number">' + month_str[month] + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="sideTopAlbum' + month + 'Loader"/><table id="sideTopAlbum' + month + '" class="side_table"></table><div class="more"><a href="/album/' + year + '/' + pad_month + '" title="Browse more">More</a></div></div><div class="container"><hr /></div>').appendTo($('#sideTable'));
      var vars = {
        container:'#sideTopAlbum' + month,
        hide:{
          artist:true,
          calendar:true,
          date:true,
          spotify:true
        },
        limit:5,
        template:'/ajax/sideTable'
      }
      view.getTopAlbum(year + '-' + pad_month + '-00', year + '-' + pad_month + '-31', vars);
    }
  },
  getTopAlbumDaily: function (year, month) {
    var str = '' + month;
    var pad = '00';
    var pad_month = pad.substring(0, pad.length - str.length) + str;
    var weekday = new Array(7);
    weekday[0]=  'Sunday';
    weekday[1] = 'Monday';
    weekday[2] = 'Tuesday';
    weekday[3] = 'Wednesday';
    weekday[4] = 'Thursday';
    weekday[5] = 'Friday';
    weekday[6] = 'Saturday';
    for (var day = 1; day <= new Date(year, month, 0).getDate(); day++) {
      var str = '' + day;
      var pad_day = pad.substring(0, pad.length - str.length) + str;
      $('<div class="container"><div><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="sideTopAlbum' + day + 'Loader"/><span id="sideTopAlbum' + day + '" class="number"></span> listenings <div class="metainfo">' + weekday[new Date(year, month, day).getDay()] + ' – <span class="number">' + app.getGetOrdinal(day) + '</span></div></div><div class="more"><a href="/album/' + year + '/' + pad_month + '/' + pad_day + '" title="Browse more">More</a></div></div>').appendTo($('#sideTable'));
      var vars = {
        container:'#sideTopAlbum' + day,
        limit:100,
      }
      view.dailyAlbumCount(year + '-' + pad_month + '-' + pad_day, vars);
    }
  },
  getListenings: function (date, vars) {
    $.ajax({
      data:{
        date:date,
        limit:vars.limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
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
        },
        400: function () { // 400 Bad request
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  getUsers: function (date, vars) {
    $('<div class="container"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/><table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table></div>').appendTo('#sideTable');
    $.ajax({
      data:{
        limit:14,
        lower_limit:date,
        sub_group_by:'album',
        upper_limit:date
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  }
});

$(document).ready(function () {
  if ('<?=$day?>' === '') {
    if ('<?=$month?>' !== '') {
      view.getTopAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
      view.getTopAlbumDaily('<?=$year?>', '<?=$month?>');
    }
    else if ('<?=$year?>' !== '') {
      view.getTopAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
      view.getTopAlbumMonthly('<?=$year?>');
    }
    else {
      view.getTopAlbum10('<?=$lower_limit?>');
      view.getTopAlbumYearly();
    }
  }
  else {
    $('#topAlbum10, #topAlbum10Loader').hide();
    $('#topAlbum').removeClass('column_table').addClass('music_table');
    var vars = {
      container:'#topAlbum',
      hide:{
        calendar:true,
        count:true,
        date:true,
        rank:true,
        spotify:true
      },
      template:'/ajax/musicTable'
    }
    var str = '' + '<?=$month?>';
    var pad = '00';
    var pad_month = pad.substring(0, pad.length - str.length) + str;
    var str = '' + '<?=$day?>';
    var pad_day = pad.substring(0, pad.length - str.length) + str;
    view.getListenings('<?=$year?>' + '-' + pad_month + '-' + pad_day, vars);
    view.getUsers('<?=$year?>' + '-' + pad_month + '-' + pad_day, vars);
  }
});
