$.extend(view, {
  topArtist10: function (interval) {
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
        group_by:'`artist_id`',
        limit:8,
        lower_limit:lower_limit,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'artist'
            },
            success: function (data) {
              $('#topArtist10Loader, #topArtist10Loader2').hide();
              $('#topArtist10').html(data);
            },
            type:'POST',
            url:'/ajax/artistList'
          });
        },
        204: function () { // 204 No Content
          $('#topArtist10Loader, #topArtist10Loader2').hide();
          $('#topArtist10').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
    view.topArtist(lower_limit);
  },
  topArtist: function (lower_limit, upper_limit = false, vars = false) {
    if (!upper_limit) {
      vars = {
        container:'#topArtist',
        hide:{
          album:true,
        },
        limit:'8, 200',
        rank:9,
        template:'/ajax/columnTable'
      }
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data:{
        group_by:'`artist_id`',
        limit:vars.limit,
        lower_limit:lower_limit,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
              rank:vars.rank,
              size:32,
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
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topArtistYearly: function () {
    for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><div class="lds-facebook loader" id="sideTopArtist' + year + 'Loader"><div></div><div></div><div></div></div><table id="sideTopArtist' + year + '" class="side_table"></table><div class="more"><a href="/<?=$tag_type?>/' + year + '/<?=$type?>" title="Browse more">More <span class="number">' + year + '</span></</a></div></div><div class="container"><hr /></div>').appendTo($('#sideTable'));
      var vars = {
        container:'#sideTopArtist' + year,
        hide:{
          album:true,
          calendar:true,
          date:true,
          spotify:true
        },
        limit:5,
        rank:1,
        template:'/ajax/sideTable'
      }
      view.topArtist(year + '-00-00', year + '-12-31', vars);
    }
  }
});

$(document).ready(function () {
  view.topArtist10('<?=$top_artist_tag_artist?>');
  view.topArtistYearly();
});