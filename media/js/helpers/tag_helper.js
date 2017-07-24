$.extend(view, {
  populateTagsMenu: function (type, order_by) {
    return $.ajax({
      data:{
        limit:1000,
        lower_limit:'1970-01-01',
        order_by:order_by,
        username:'<?=!empty($_GET['u']) ? $_GET['u'] : ''?>'
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
    $('html').on('click', '#addtags', function () {
      $('#tagAdd').toggle();
      $('.search-field input[type="text"]').focus();
    });
    $('html').on('mouseover', '.tag', function () {
      $(this).find('.remove').show();
    });
    $('html').on('mouseout', '.tag', function () {
      $(this).find('.remove').hide();
    });
    $('html').on('click', '.remove', function () {
      var type = $(this).data('tag-type');
      $.ajax({
        data:{
          album_id:<?=$album_id?>,
          tag_id: parseInt($(this).data('tag-id'))
        },
        statusCode:{
          200: function (data) {
            view.getTags();
          }
        },
        url:'/api/' + type + '/delete',
        type:'POST'
      });
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
