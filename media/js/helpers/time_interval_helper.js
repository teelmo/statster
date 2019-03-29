$(document).ready(function () {
  $('.func_container .value').click(function() {
    var sub_nav = $(this).parent().find('ul.subnav');
    if (sub_nav.is(':visible')) {
      $(this).removeClass('active');
      sub_nav.hide();        
    }
    else {
      $('.func_container .subnav').hide();
      $('.func_container .value').removeClass('active');
      $(this).addClass('active');
      sub_nav.show();
    }
  }).hover(function() {
    $(this).addClass('subhover');
  }, function() {
    $(this).removeClass('subhover');
  });
  $('.func_container .subnav li').click(function() {
    $('.func_container .subnav').hide();
    $('.func_container .value').removeClass('active');
    $('#' + $(this).parent('ul').data('loader')).show();
    var name = $(this).parent('ul').data('name');
    var callback = $(this).parent('ul').data('callback');
    var value = $(this).data('value');
    $(this).parent('ul').parent('.func_container').find('.value').text($(this).text());
    $.ajax({
      data:{
        name:name,
        value:value
      },
      dataType:'json',
      statusCode:{
        204: function () { // 204 No Content
          view[callback](value);
        },
        400: function () {
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/user/update/interval/'
    });
  });
});