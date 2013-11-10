$(document).ready(function() {
  $('#loginUsername').focus();

  $('#loginSubmit').click(function() {
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
          window.location.href = '/';
        }
        else {
          alert('Wrong username or password. Please try again.');
        }
      }
    });
    return false;
  });
});

