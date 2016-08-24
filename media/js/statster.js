var app = {
  compareStrings: function (a, b) {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  getGetOrdinal: function (n) {
    var s=['th','st','nd','rd'];
    var v = n % 100;
    return n + (s[(v - 20) % 10] || s[v] || s[0]);
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
            item_arr[0] = item_arr[0].trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            item_arr[1] = item_arr[1].trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            if (item.label != item_arr[0] + ' – ') {
              if (item.img) {
                return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.img + ')"></div>' + String(item.label).replace(new RegExp(item_arr[0] + '|' + item_arr[1] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
              }
              else {
                return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><span class="no_img">' + String(item.label).replace(new RegExp(item_arr[0] + '|' + item_arr[1] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</span></a>').appendTo(ul);
              }
            }
            else {
              $('#addListeningText').attr('data-placeholder', this.term + ' (yyyy)');
              return $('<li></li>');
            }
          }
          else {
            item_arr[0] = item_arr[0].trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&").replace(/<\/?[^>]+(>|$)/g, '');
            if (item.img) {
              return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.img + ')"></div>' + String(item.label).replace(new RegExp(item_arr[0] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
            }
            else {
              return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><span class="no_img">' + String(item.label).replace(new RegExp(item_arr[0] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</span></a>').appendTo(ul);
            }
          }
        }
        else {
          this.term = this.term.trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&").replace(/<\/?[^>]+(>|$)/g, '');
          if (item.img) {
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.img + ')"></div>' + String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
          }
          else {
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><span class="no_img">' + String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>') + '</span></a>').appendTo(ul);
          }
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
      $('.search_text').focus();
    });
  },
  initToolTipster: function () {
    // http://iamceege.github.io/tooltipster/
    $('.tooltip').tooltipster({
      theme: 'tooltipster-shadow'
    });
  },
  initStatsterEvents: function () {
    $('.search_text').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      source:'/autoComplete/search',
      select: function (event, ui) {
        if (ui.item.url !== undefined) {
          window.location = ui.item.url;
        }
      },
      search: function () {
        $(this).addClass('working');
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
    $(window).scroll(function () {
      if ($(window).scrollTop() > 5) {
        if ($('#headingCont').length !== 0) {
          $('#topCont').addClass('scrolled');
        }
      }
      else {
        if ($('#headingCont').length !== 0) {
          $('#topCont').removeClass('scrolled');
        }
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
  app.initToolTipster();
  if ($('#headingCont').length === 0) {
    $('#topCont').addClass('scrolled');
  }
});