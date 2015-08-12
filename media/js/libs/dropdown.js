$(document).ready(function() {
  // User dropdown menu
  $('.userContDropdown').click(function() {
    var sub_nav = $(this).parent().find('ul.subnav');
    if(sub_nav.is(':visible')) {
      sub_nav.slideUp('fast');
    }
    else {
      sub_nav.slideDown('fast').show();
      $(this).parent().hover(function() {
      }, function() {
        sub_nav.slideUp('slow');
      });
    }
  }).hover(function() {
    $(this).addClass('subhover');
  }, function() {
    $(this).removeClass('subhover');
  });
});