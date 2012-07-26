jQuery('#loginUsername').focus();

jQuery("#loginSubmit").click(function() {
  jQuery.ajax({
    type: 'POST', url: '/api/login',
    data: {
      username : jQuery('#loginUsername').val(),
      password : jQuery('#loginPassword').val(),
      submitType : jQuery('input[name="submitType"]').val(),
    },
    success: function(data) {
      if (data == '') {  
        window.location.href = "/";
      }
      else {
        // @TODO error msgs.
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
  return false;
});