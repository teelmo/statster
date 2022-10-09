var app = {
  compareStrings: function (a, b) {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  getGetOrdinal: function (n) {
    var s = ['th','st','nd','rd'];
    var v = n % 100;
    return n + (s[(v - 20) % 10] || s[v] || s[0]);
  },
  formatNr: function (x) {
    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return (x == '') ? 0 : x;
  },
  highlightPatch: function () {
    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
      if (item.value === '') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(item.label).appendTo(ul);
      }
      else if (item.value === 'search') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append('<a>' + item.label + '</a>').appendTo(ul);
      }
      else {
            console.log(item)
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
  initToolTipster: function () {
    // http://iamceege.github.io/tooltipster/
    $('.tooltip').tooltipster({
      theme:'tooltipster-shadow'
    });
  },
  initStatsterEvents: function () {
    $('.search_text').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      select: function (event, ui) {
        if (ui.item.url !== undefined) {
          window.location = ui.item.url;
        }
      },
      search: function () {
        $(this).addClass('working');
      },
      source:'/api/search/get/10/'
    });
    $('.search_text').keyup(function () {
      ($(this).val() !== '') ? $('.search_submit').prop('disabled', false) : $('.search_submit').prop('disabled', true);
    });
    $('.settings a').click(function () {
      $(this).parent('.settings').find('a').addClass('unactive');
      $(this).removeClass('unactive');
    });
    $('.user_container').click(function() {
      var sub_nav = $(this).parent().find('ul.subnav');
      if (sub_nav.is(':visible')) {
        $(this).removeClass('active');
        sub_nav.slideUp('fast');
      }
      else {
        $(this).addClass('active');
        sub_nav.slideDown('fast').show();
        $(this).parent().hover(function() {
        }, function() {
          // sub_nav.slideUp('slow');
        });
      }
    }).hover(function() {
      $(this).addClass('subhover');
    }, function() {
      $(this).removeClass('subhover');
    });
    $('.toggle_username').click(function () {
      if ($(this).hasClass('active')) {
        $.ajax({
          dataType:'json',
          statusCode:{
            200: function () { // 200 OK
              location.reload();
            }
          },
          type:'GET',
          url:'/Ajax/selectYourself/delete'
        });
      }
      else {
        $.ajax({
          dataType:'json',
          statusCode:{
            200: function () { // 200 OK
              location.reload();
            }
          },
          type:'GET',
          url:'/Ajax/selectYourself/add'
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
    $('html').on('mouseover', '.music_wall li, .music_list li', function (event) {
      $(this).addClass('hover');
      event.stopPropagation();
    });
    $('html').on('mouseout', '.music_wall li, .music_list li', function (event) {
      $(this).removeClass('hover');
      event.stopPropagation();
    });
    $('html').on('mouseover', '.music_wall li .meta, .music_list li .meta', function (event) {
      event.stopPropagation();
    });
    $('html').on('mouseout', '.music_wall li .meta, .music_list li .meta', function (event) {
      event.stopPropagation();
    });
    $('html').on('click', '.some_link', function () {
      var specs = 'top=' + ((screen.height / 2) - (420 / 2)) + ',left=' + ((screen.width / 2) - (550 / 2)) + ',toolbar=0,status=0,width=550,height=420';
      window.open($(this).data('url') + window.location.href, 'Share', specs);
    });
  }
}
var view = {}

$(document).ready(function () {
  if (document.images) {
    var preLoadImg1 = new Image();
    preLoadImg1.src = '/media/img/ajax-loader-bar.gif';
    var preLoadImg2 = new Image();
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