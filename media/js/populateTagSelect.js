var view = {
  populateTagsMenu: function(type) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/' + type + '/get',
      data: {
        limit:1000,
        lower_limit:'1970-01-01',
        order_by:'name',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      success: function(data) {
        $.each(data, function(i, value) {
          $('#' + type).append('<option class="' + type + '" name="' + value.name + '">' + value.name + '</option>')
        });
      }
    });
  }
}

$(document).ready(function() {
  view.populateTagsMenu('genre');
  view.populateTagsMenu('keyword');
  view.populateTagsMenu('nationality');
});
