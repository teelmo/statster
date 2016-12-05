$.extend(view, {
  initLoginEvents: function () {
    $('#loginUsername').focus();
    $('#loginSubmit').click(function () {
      $.ajax({
        data:{
          password:$('#loginPassword').val(),
          submitType:$('input[name="submitType"]').val(),
          username:$('#loginUsername').val()
        },
        success: function (data) {
          if (data.trim() === '') {
            window.location.href = encodeURI('<?=addslashes($redirect)?>').replace(/%20/g,'+');
          }
          else {
            alert('Wrong username or password. Please try again.');
          }
        },
        type:'POST',
        url:'/api/login'
      });
      return false;
    });
  }
});

$(document).ready(function() {
  view.initLoginEvents();
});

