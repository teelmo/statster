$.extend(view, {
  populateTagsMenu: function (type, order_by) {
    return $.ajax({
      data:{
        limit:1000,
        lower_limit:'1970-01-01',
        order_by:order_by,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.each(data, function (i, value) {
            $('<option class="' + type + '" value="' + type + ':' + value['tag_id'] + '">' + value['name'] + '</option>').appendTo($('#' + type));
          });
        }
      },
      url:'/api/' + type + '/get',
      type:'GET'
    });
  },
  initTagHelperEvents: function () {
    $('html').on('click', '#moretags', function () {
      $('#tagAdd').toggle();
      $('.search-field input[type="text"]').focus();
    });
  }
});

$(document).ready(function () {
  view.initTagHelperEvents();
  $.when(
    view.populateTagsMenu('genre', 'name'),
    view.populateTagsMenu('keyword', 'name'),
    view.populateTagsMenu('nationality', 'country')
  ).done(function () {
    $(document).ajaxStop(function () {
      $('#tagAdd select').chosen();
    });
  });
});
