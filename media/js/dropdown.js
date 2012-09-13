jQuery(document).ready(function() {
  // User dropdown menu
  jQuery("#userContDropdown").click(function() {
    var sub_nav = jQuery(this).parent().find('ul.subnav');
    // Check if 
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
    jQuery(this).addClass('subhover');
  }, function() {
    jQuery(this).removeClass('subhover');
  });
});