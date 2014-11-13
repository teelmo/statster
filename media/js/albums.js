$(document).ready(function() {
  topAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
  vars = {
    container:'#topAlbum',
    limit:'8, 200',
    template:'/ajax/barTable'
  }
  topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>', vars);
  topAlbumYearly();
});

function topAlbum10(lower_limit, upper_limit) {
  
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/album/get',
    data: {
      limit:8,
      lower_limit:lower_limit,
      upper_limit:upper_limit,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/albumList/124',
          data: {
            json_data:data
          },
          success: function(data) {
        console.log('asd')
            $('#topAlbum10Loader').hide();
            $('#topAlbum10').html(data);
          }
        });
      }
    }
  });
}

function topAlbum(lower_limit, upper_limit, vars) {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/album/get',
    data: {
      limit:vars.limit,
      lower_limit:lower_limit,
      upper_limit:upper_limit,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        $.ajax({
          type:'POST',
          url:vars.template,
          data: {
            json_data:data,
            size:32,
            rank:9,
            hide:vars.hide
          },
          success: function(data) {
            $(vars.container + 'Loader').hide();
            $(vars.container + '').html(data);
          }
        });
      }
    }
  });
}

function topAlbumYearly() {
  for (var year = <?=CUR_YEAR?>; year >= 2003; year--) {
    $('<div class="container"><h2>' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist' + year + 'Loader"/><table id="topArtist' + year + '" class="sideTable"></table><div class="more"><a href="/album/' + year + '" title="Browse more">See more</a></div></div><div class="container"><hr /></div>').appendTo($('#years'));
    vars = {
      container:'#topArtist' + year,
      limit:'0, 5',
      template:'/ajax/sideTable',
      hide: {
        calendar:true,
        date:true,
        artist:true
      }
    }
    topAlbum(year + '-00-00', year + '-12-31', vars);
  }
}
