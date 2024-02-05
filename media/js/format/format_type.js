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
        format_name:'<?=$format_name?>',
        format_type_name:'<?=$format_type_name?>',
        hide:{},
        limit:8,
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
          console.log(data)
          $('#topAlbum10Loader, #topAlbum10Loader2').hide();
          $('#topAlbum10').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
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
        format_name:'<?=$format_name?>',
        format_type_name:'<?=$format_type_name?>',
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
      url:'/api/format/get'
    });
  },
  getFormats: function () {
    $.ajax({
      data:{
        limit:10,
        sub_group_by:'album',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                format_icon:true
              },
              json_data:data
            },
            success: function (data) {
              $('#topListeningFormatTypesLoader').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  }
});

$(document).ready(function () {
  view.getTopAlbum10('<?=$lower_limit?>');
  view.getFormats();
});
