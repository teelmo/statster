$.extend(view, {
  getListeningCumulation: function (type) {
    $.ajax({
      data:{
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          view.initGraph(data);
        },
        204: function () { // 204 No Content
          $('.line').hide();
        },
        400: function () { // 400 Bad request
          $('.line').hide();
        }
      },
      type:'GET',
      url:'/api/listener/get/cumulative'
    });
  },
  // Get listening by year.
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') IS NOT NULL AND DATE_FORMAT(<?=TBL_listening?>.`date`, \'%d\') != \'00\'';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      data:{
        group_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')',
        limit:200,
        order_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
        username:'<?=(!empty($username)) ? $username: ''?>',
        where:where
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
          $('#history').html('<?=ERR_NO_RESULTS?>');
          $('.music_bar').hide();
        },
        400: function () { // 400 Bad request
          $('#historyLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
          $('.music_bar').hide();
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    if (isFirst != true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      data:{
        limit:12,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                del:true
              },
              json_data:data,
              strlenght:50
            },
            success: function (data) {
              $('#recentlyListenedLoader2').hide();
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
              var currentTime = new Date();
              var hours = currentTime.getHours();
              var minutes = currentTime.getMinutes();
              if (minutes < 10) {
                minutes = '0' + minutes;
              }
              $('#recentlyUpdated').html('updated <span class="number">' + hours + '</span>:<span class="number">' + minutes + '</span>');
              $('#recentlyUpdated').attr('value', currentTime.getTime());
            },
            type:'POST',
            url:'/ajax/musicTable'
          })
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  // Get top albums.
  getTopAlbums: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:9,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbumLoader, #topAlbumLoader2').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader, #topAlbumLoader2').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  // Get top artists.
  getTopArtists: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
      data:{
        limit:9,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'artist'
            },
            success: function (data) {
              $('#topArtistLoader, #topArtistLoader2').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader, #topArtistLoader2').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      }
    });
  },
  getShouts: function () {
    $.ajax({
      data:{
        username:'<?=$username?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          if (data[0].count == 1) {
            $('#shoutTotal').html('<span class="number">' + data[0].count + '</span> shout').fadeIn(500);
          }
          else {
            $('#shoutTotal').html('<span class="number">' + data[0].count + '</span> shouts').fadeIn(500);
          }
          $.ajax({
            data:{
              hide:{
                user:true
              },
              json_data:data,
              size:64,
              type:'user'
            },
            success: function (data) {
              $('#userShoutLoader').hide();
              $('#userShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        },
        204: function () { // 204 No Content
          $('#shoutLoader').hide();
          $('#shout').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/shout/get/user'
    });
  },
  getAlbumShouts: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=$username?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                delete:true,
                user:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#albumShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  getArtistShouts: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=$username?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                delete:true,
                user:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#artistShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/artist'
    });
  },
  recentlyFaned: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true,
                user:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyFaned').html(data);
            },
            type:'POST',
            url:'/ajax/likeTable'
          });
        }
      },
      type:'GET',
      url:'/api/fan/get'
    });
  },
  recentlyLoved: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true,
                user:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyLoved').html(data);
            },
            type:'POST',
            url:'/ajax/likeTable'
          });
        }
      },
      type:'GET',
      url:'/api/love/get'
    });
  },
  getTopFormats: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topFormatLoader, #topFormatLoader2').hide();
              $('#topFormat').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topFormatLoader, #topFormatLoader2').hide();
          $('#topFormat').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  },
  getTopGenres: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader, #topGenreLoader2').hide();
              $('#topGenre').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topGenreLoader, #topGenreLoader2').hide();
          $('#topGenre').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  getTopKeywords: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader, #topKeywordLoader2').hide();
              $('#topKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topKeywordLoader, #topKeywordLoader2').hide();
          $('#topKeyword').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/keyword/get'
    });
  },
  getTopNationalities: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topNationalityLoader, #topNationalityLoader2').hide();
              $('#topNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topNationalityLoader, #topNationalityLoader2').hide();
          $('#topNationality').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/nationality/get/listenings'
    });
  },
  getTopYears: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topYearLoader, #topYearLoader2').hide();
              $('#topYear').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topYearLoader, #topYearLoader2').hide();
          $('#topYear').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/year/get'
    });
  },
  initProfileEvents: function () {
    $(document).ajaxStop(function (event, request, settings) {
      $('#musicShout').append($('.shouts tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#musicShoutLoader').hide();

      $('#recentlyLiked').append($('.likes tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#recentlyLikedLoader').hide();
    });
    $('#refreshRecentAlbums').click(function () {
      view.getRecentListenings();
    });
  }
});

$(document).ready(function () {
  view.getListeningHistory('%Y');
  view.getListeningCumulation();
  view.getRecentListenings();
  view.getTopAlbums('<?=$top_album_profile?>');
  view.getTopArtists('<?=$top_artist_profile?>');
  view.getShouts();
  view.getAlbumShouts();
  view.getArtistShouts();
  view.recentlyFaned();
  view.recentlyLoved();
  view.getTopFormats('<?=$top_listening_format_profile?>');
  view.getTopGenres('<?=$top_genre_profile?>');
  view.getTopKeywords('<?=$top_keyword_profile?>');
  view.getTopNationalities('<?=$top_nationality_profile?>');
  view.getTopYears('<?=$top_year_profile?>');
  view.initProfileEvents();
});