$.extend(view, {
  topAlbum10: function (lower_limit, upper_limit) {  
    $.ajax({
      data:{
        limit:10,
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
              $('#topAlbum10Loader').hide();
              $('#topAlbum10').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124'
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbum10Loader').hide();
          $('#topAlbum10').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topAlbum: function (lower_limit, upper_limit, vars) {
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
              rank:11,
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
          $(vars.container).html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topAlbumYearly: function () {
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
      view.topAlbum(year + '-00-00', year + '-12-31', vars);
    }
  },
  topAlbumMonthly: function (year) {
    for (var month = 1; month <= 12; month++) {
      var month_str = new Array();
      month_str[1] = "January";
      month_str[2] = "February";
      month_str[3] = "March";
      month_str[4] = "April";
      month_str[5] = "May";
      month_str[6] = "June";
      month_str[7] = "July";
      month_str[8] = "August";
      month_str[9] = "September";
      month_str[10] = "October";
      month_str[11] = "November";
      month_str[12] = "December";
      var str = '' + month;
      var pad = '00';
      var pad_month = pad.substring(0, pad.length - str.length) + str;
      $('<div class="container"><h2 class="number">' + month_str[month] + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="sideTopAlbum' + month + 'Loader"/><table id="sideTopAlbum' + month + '" class="side_table"></table><div class="more"><a href="/artist/' + year + '/' + pad_month + '" title="Browse more">More <span class="number">' + month_str[month] + '</span></a></div></div><div class="container"><hr /></div>').appendTo($('#sideTable'));
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
      view.topAlbum(year + '-' + pad_month + '-00', year + '-' + pad_month + '-31', vars);
    }
  }
});

$(document).ready(function () {
  view.topAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
  var vars = {
    container:'#topAlbum',
    limit:'10, 200',
    template:'/ajax/columnTable'
  }
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  if ('<?=$side_title?>' === 'Monthly') {
    view.topAlbumMonthly('<?=$year?>');
  }
  else {
    view.topAlbumYearly();
  }
});
