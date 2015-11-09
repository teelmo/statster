$.extend(view, {
  populateTagsMenu: function (type, order_by) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/' + type + '/get',
      data:{
        limit:1000,
        lower_limit:'1970-01-01',
        order_by:order_by,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) {
          $.each(data, function (i, value) {
            $('#' + type).append('<option class="' + type + '" name="' + value.name + '">' + value.name + '</option>');
          });
        }
      }
    });
  }
});

$(document).ready(function () {
  $.when(
    view.populateTagsMenu('genre', 'name'),
    view.populateTagsMenu('keyword', 'name'),
    view.populateTagsMenu('nationality', 'country')
  ).done(function () {
    $('#tagAddSelect').trigger('chosen:updated');
  });
});
