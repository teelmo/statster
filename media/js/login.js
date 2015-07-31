$.extend(view, {
  initEvents: function () {
    $('#loginUsername').focus();

    $('#loginSubmit').click(function() {
      $.ajax({
        type:'POST',
        url:'/api/login',
        data: {
          password:$('#loginPassword').val(),
          submitType:$('input[name="submitType"]').val(),
          username:$('#loginUsername').val()
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
  }
});

$(document).ready(function() {
  view.initEvents();
});

