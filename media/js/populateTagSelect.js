$(document).ready(function() {
  populateTagsMenu('genre');
  populateTagsMenu('keyword');
  populateTagsMenu('nationality');
});

function populateTagsMenu(type) {
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
        $('#' + type).append('<option>' + value.name + '</option>')
      });
      $("#tagAddSelect").trigger("liszt:updated");
    }
  });
}
