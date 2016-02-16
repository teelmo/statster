var app = {
  compareStrings: function (a, b) {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  formatNr: function (x) {
    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return (x == '') ? 0 : x;
  },
  highlightPatch: function () {
    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
      if (item.value == '') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(item.label).appendTo(ul);
      }
      else {
        if (this.term.indexOf('–') !== -1) {
          var item_arr = this.term.split('–');
          if (item_arr[1] != '') {
            item_arr[0] = item_arr[0].trim();
            item_arr[1] = item_arr[1].trim();
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(item_arr[0].replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '|' + item_arr[1].replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
          }
          else {
            item_arr[0] = item_arr[0].trim();
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(item_arr[0].replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);

          }
        }
        else {
          this.term = this.term.trim();
          return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(this.term.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
        }
      }
    }
  },
  // select: function (event, ui) {
  //   event.preventDefault();
  //   if (ui.item.value != 'label') {
  //     return;
  //   }
  // },
  initMouseTrap: function () {
    Mousetrap.bind(['mod+k'], function (e) {
      window.location = '/';
    });
    Mousetrap.bind(['mod+shift+s'], function (e) {
      $('#searchString').focus();
    });
  },
  initStatsterEvents: function () {
    $('.search_text').autocomplete({
      minLength:3,
      html:true,
      source:'/autoComplete/search',
      select: function (event, ui) {
        window.location = ui.item.url;
      },
      search: function () {
        $(this).addClass('working');
      },
      open: function () {
        $(this).removeClass('working');
      }
    });
    $('.settings a').click(function () {
      $(this).parent('.settings').find('a').addClass('unactive');
      $(this).removeClass('unactive');
    });
    $('.toggle_username').click(function () {
      if ($(this).hasClass('active')) {
        $.ajax({
          type:'GET',
          dataType:'json',
          url:'/Ajax/selectYourself/delete',
          statusCode:{
            200: function () { // 200 OK
              location.reload();
            }
          }
        });
      }
      else {
        $.ajax({
          type:'GET',
          dataType:'json',
          url:'/Ajax/selectYourself/add',
          statusCode:{
            200: function () { // 200 OK
              location.reload();
            }
          }
        });
      } 
    });
  }
}
var view = {}

$(document).ready(function () {
  if (document.images) {
    preLoadImg1 = new Image();
    preLoadImg1.src = '/media/img/ajax-loader-bar.gif';
    preLoadImg2 = new Image();
    preLoadImg2.src = '/media/img/ajax-loader-circle.gif';
  }

  app.highlightPatch();
  app.initMouseTrap();
  app.initStatsterEvents();
});