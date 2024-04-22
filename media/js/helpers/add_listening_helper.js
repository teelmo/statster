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
    var curday = function (sp) {
      today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      return (yyyy + sp + mm + sp + dd);
    };
    $('#addListeningDate').val(curday('-'));
    $('#addListeningDate').dateRangePicker({
      autoClose:true,
      endDate:'<?=date('Y-m-d',strtotime(CUR_DATE . "+1 days"))?>',
      hoveringTooltip:false,
      showShortcuts:false,
      showTopbar:false,
      singleDate:true,
      singleMonth:true,
      startOfWeek:'monday',
      customArrowPrevSymbol:'<i class="fa fa-angle-left"></i>',
      customArrowNextSymbol:'<i class="fa fa-angle-right"></i>'
    });
    $('#addListeningDate').change(function () {
      setTimeout(function () {
        $('#addListeningDate').val(curday('-'));
      }, 60 * 2 * 1000); // 
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
    $('.listening_format').click(function (event) {
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
        $('#' + $(this).parent().attr('for')).prop('checked', false).trigger('change');
      }
      else {
        $('.listening_format').removeClass('selected');
        $(this).addClass('selected');
        $('#' + $(this).parent().attr('for')).prop('checked', true).trigger('change');
      }
      event.preventDefault();
      event.stopPropagation();
      return false;
    });
    // Listening format keypress.
    $('.listening_format').keypress(function (event) {
      var code = (event.keyCode ? event.keyCode : event.which);
      if (code === 13) {
        if ($(this).hasClass('selected')) {
          $(this).removeClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', false).trigger('change');
        }
        else {
          $('.listening_format').removeClass('selected');
          $(this).addClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', true).trigger('change');
        }
      }
      event.preventDefault();
      event.stopPropagation();
      return false;
    });
    $('#addListeningSubmit').click(function () {
      var text_value = $('#addListeningText').val();
      if (text_value === '') {
        return false;
      }
      var format_value = $('input[name="addListeningFormat"]:checked').val()
      var album_id = ($('#addListeningText').data('ui-autocomplete').selectedItem) ? $('#addListeningText').data('ui-autocomplete').selectedItem.album_id : false ;
      var artist_ids = ($('#addListeningText').data('ui-autocomplete').selectedItem) ? $('#addListeningText').data('ui-autocomplete').selectedItem.artist_ids : false ;
      $('#recentlyListenedLoader2').show();
      $('#addListeningText').val('');
      $('input[name="addListeningFormat"]').prop('checked', false);
      $('.listening_format').removeClass('selected');
      $.ajax({
        data:{
          album_id:album_id,
          artist_ids:artist_ids,
          created:new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000)).toISOString().slice(0, 19).replace('T', ' '),
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
            if (view.getUsers) {
              view.getUsers();
            }
            if ($('.tag_meta div.value').length > 0) {
              $('.tag_meta div.value').data('value', parseInt($('.tag_meta div.value').data('value')) + 1);
              $('.tag_meta div.value').html((parseInt($('.tag_meta div.value').data('value'))).toLocaleString());
            }
            if ($('.tag_meta span.user_value').length > 0) {
              $('.tag_meta span.user_value .value').data('value', parseInt($('.tag_meta span.user_value .value').data('value')) + 1);
              $('.tag_meta span.user_value .value').html((parseInt($('.tag_meta span.user_value .value').data('value'))).toLocaleString());
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