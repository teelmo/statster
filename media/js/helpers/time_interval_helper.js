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
      $(this).parent().hover(function() {
      }, function() {
        // sub_nav.slideUp('slow');
      });
    }
  }).hover(function() {
    $(this).addClass('subhover');
  }, function() {
    $(this).removeClass('subhover');
  });
  $('.func_container .subnav li').click(function() {
    console.log($(this).parent('ul').data('name'))
    console.log($(this).data('value'))
    $.ajax({
      data:{
        name:$(this).parent('ul').data('name'),
        value:$(this).data('value')
      },
      dataType:'json',
      statusCode:{
        204: function () { // 204 No Content
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