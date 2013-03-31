$(document).ready(function() {
  jQuery('#loginUsername').focus();

  $("#loginSubmit").click(function() {
    $.ajax({
      type:'POST',
      url:'/api/login',
      data: {
        username:$('#loginUsername').val(),
        password:$('#loginPassword').val(),
        submitType:$('input[name="submitType"]').val(),
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
});

