$.extend(view, {
  // Get album love.
  getLove: function (user_id) { 
    if (user_id === undefined) {
      $('#loveLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#loveLoader').hide();
      },
      data:{
        user_id:user_id
      },
      dataType:'json',
      statusCode:{
        200: function () { // 200 OK
          $('#love').addClass('love_del');
        },
        204: function () { // 204 No Content
          $('#love').addClass('love_add');
        },
        400: function () {
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/love/get/<?=$album_id?>'
    });
  },
  // Get album loves.
  getLoves: function () {
    $.ajax({
      data:{},
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{},
              json_data:data
            },
            success: function (data) {
              $('#albumLoveLoader').hide();
              $('#albumLove').html(data);
            },
            type:'POST',
            url:'/ajax/likeList'
          });
        },
        204: function () { // 204 No Content
          $('#albumLoveLoader').hide();
        },
        400: function () { // 400 Bad request
          $('#albumLoveLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/love/get/<?=$album_id?>'
    });
  },
  // Get album listeners.
  getUsers: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:6
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
  },
  // Get album listenings.
  getListenings: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:6,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true,
                spotify:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') IS NOT NULL';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listener/get',
      data:{
        artist_name:'<?=$artist_name?>',
        album_name:'<?=$album_name?>',
        group_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')',
        limit:200,
        order_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:where
      },
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
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
        }
      }
    });
  },
  initAlbumEvents: function () {
    $('#moretags').click(function() {
      $('#tagAdd').toggle();
      if ($(this).text() == '+') {
        $(this).html('<a href="javascript:;">-</a>');
        $('.search-field input[type="text"]').focus();
      }
      else {
        $(this).html('<a href="javascript:;">+</a>');
      }
    });
    $('html').on('click', '#love', function () {
      $('.loveMsg').remove();
      if ($(this).hasClass('love_add')) {
        $.ajax({
          data:{},
          statusCode:{
            201: function (data) { // 201 Created
              $('#love').removeClass('love_add').addClass('love_del').prepend('<span class="like_msg">You\'re in love!</span>');
              setTimeout(function() {
                $('.like_msg').fadeOut('slow');
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'POST',
          url:'/api/love/add/<?=$album_id?>'
        });
      }
      if ($(this).hasClass('love_del')) {
        $.ajax({
          data:{},
          statusCode:{
            204: function () { // 204 No Content
              $('#love').removeClass('love_del').addClass('love_add').prepend('<span class="like_msg">You\'re no longer in love!</span>');
              setTimeout(function() {
                $('.like_msg').fadeOut('slow');
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'DELETE',
          url:'/api/love/delete/<?=$album_id?>'
        });
      }
    });
  }
});

$(document).ready(function () {
  view.getLove(<?=$this->session->userdata('user_id')?>);
  view.getLoves();
  view.getListeningHistory('%Y');
  view.getUsers();
  view.getListenings();
  view.initAlbumEvents();
});
