$.extend(view, {
  initAutocomplete: function () {
    $('#addListeningText').focus();
    $('#addListeningText').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      source:'/autoComplete/addListening',
      search: function () {
        $(this).addClass('working');
      }
    });
  },
  initDatepicker: function () {
    $('#addListeningDate').datepicker({
      dateFormat:'yy-mm-dd',
      firstDay:1,
      maxDate:'today',
      selectOtherMonths:true,
      showAnim:'slideDown',
      showOtherMonths:true
    });
    $('#addListeningDate').change(function () {
      setTimeout(function () {
        $('#addListeningDate').val('<?php echo CUR_DATE?>');
      }, 60 * 2 * 1000);
    });
  },
  initKeystop: function () {
    var keyStop = {
      8:':not(input:text,textarea,input:file,input:password)',
      13:'input:text,input:password',
      end:null
    }
    $(document).bind('keydown', function (event) {
      var selector = keyStop[event.which];
      if (selector !== undefined && $(event.target).is(selector)) {
        event.preventDefault();
      }
      return true;
    });
  },
  initAddListeningHelperEvents: function () {
    // Listening format click.
    $('.listening_format').click(function () {
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
      }
      else {
        $('.listening_format').removeClass('selected');
        $(this).addClass('selected');
      }
    });
    // Listening format keypress.
    $('.listening_format').keypress(function (e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13) {
        if ($(this).hasClass('selected')) {
          $(this).removeClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', false);
        }
        else {
          $('.listening_format').removeClass('selected');
          $(this).addClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', true);
        }
      }
    });
    $('#addListeningSubmit').click(function () {
      var text_value = $('#addListeningText').val();
      if (text_value === '') {
        return false;
      }
      var format_value = $('input[name="addListeningFormat"]:checked').val()
      $('#recentlyListenedLoader2').show();
      $('#addListeningText').val('');
      $('input[name="addListeningFormat"]').prop('checked', false);
      $('.listening_format').removeClass('selected');
      $.ajax({
        data:{
          date:$('#addListeningDate').val(),
          format:format_value,
          submitType:$('input[name="submitType"]').val(),
          text:text_value
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            view.getRecentListenings();
            if (view.getTopArtists) {
              view.getTopArtists($('.top_artist_value').data('value'));
            }
            if (view.getTopAlbums) {
              view.getTopAlbums($('.top_album_value').data('value'));
            }
            $('#addListeningText').focus();
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#recentlyListenedLoader2').hide();
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#recentlyListenedLoader2').hide();
          },
          404: function () { // 404 Not found
            alert('404 Not Found');
            $('#recentlyListenedLoader2').hide();
          }
        },
        type:'POST',
        url:'/api/listening/add'
      });
      return false;
    });
  }
});

$(document).ready(function() {
  view.initAutocomplete();
  view.initDatepicker();
  view.initKeystop();
  view.initAddListeningHelperEvents();
});