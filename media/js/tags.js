$.extend(view, {
  topMusic10: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        group_by:'`<?php echo $type?>_id`',
        limit:10,
        lower_limit:lower_limit,
        order_by:'`count` DESC, <?php echo TBL_artist?>.`artist_name` ASC',
        tag_id:'<?php echo $tag_id?>',
        tag_type:'<?php echo $tag_type?>',
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topMusic10Loader').hide();
              $('#topMusic10').html(data);
            },
            type:'POST',
            url:'/ajax/<?php echo $type?>List/124',
          });
        },
        204: function () { // 204 No Content
          $('#topMusic10Loader').hide();
          $('#topMusic10').html('<?php echo ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topMusic: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        group_by:'`<?php echo $type?>_id`',
        limit:vars.limit,
        lower_limit:lower_limit,
        order_by:'`count` DESC, <?php echo TBL_artist?>.`artist_name` ASC',
        tag_id:'<?php echo $tag_id?>',
        tag_type:'<?php echo $tag_type?>',
        upper_limit:upper_limit,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
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
        204: function () { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?php echo ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topMusicYearly: function () {
    for (var year = <?php echo CUR_YEAR?>; year >= 2003; year--) {
      $('<div class="container"><h2 class="number">' + year + '</h2><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topMusic' + year + 'Loader"/><table id="topMusic' + year + '" class="side_table"></table><div class="more"><a href="/artist/' + year + '" title="Browse more">More <span class="number">' + year + '</span></a></div></div><div class="container"><hr /></div>').appendTo($('#years'));
      var vars = {
        container:'#topMusic' + year,
        hide:{
          <?php echo $hide?>,
          calendar:true,
          date:true,
          spotify:true
        },
        limit:5,
        template:'/ajax/sideTable'
      }
      view.topMusic(year + '-00-00', year + '-12-31', vars);
    }
  },
  initTagEvents: function () {
  
  }
});

$(document).ready(function () {
  view.topMusic10('<?php echo $lower_limit?>', '<?php echo $upper_limit?>');
  var vars = {
    container:'#topMusic',
    hide:{
      <?php echo $hide?>
    },
    limit:'10, 200',
    template:'/ajax/columnTable'
  }
  view.topMusic('<?php echo $lower_limit?>', '<?php echo $upper_limit?>', vars);
  view.topMusicYearly();
});