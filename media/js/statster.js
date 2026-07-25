var app = {
  compareStrings: (a, b) => {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  getGetOrdinal: n => {
    var s = ['th', 'st', 'nd', 'rd'];
    var v = n % 100;
    return n + (s[(v - 20) % 10] || s[v] || s[0]);
  },
  formatNr: x => {
    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return x === '' ? 0 : x;
  },
  substrWords: (text, maxChar = 35, end = '…') => {
    if (text.length > maxChar || text === '') {
      const words = text.split(/\s+/);
      let output = '';
      let i = 0;

      while (true) {
        const length = output.length + words[i].length;
        if (length > maxChar) {
          break;
        } else {
          output += (output ? ' ' : '') + words[i];
          i++;
          if (i >= words.length) break;
        }
      }

      output += end;
      return output;
    } else {
      return text;
    }
  },
  setOverlayBackground: image => {
    document.querySelector('.background_overlay').style.backgroundImage = `url(${image})`;
  },
  highlightPatch: () => {
    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
      var item_arr;
      if (item.value === '') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(item.label).appendTo(ul);
      } else if (item.value === 'search') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(`<a>${item.label}</a>`).appendTo(ul);
      } else {
        if (this.term.indexOf('–') !== -1) {
          item_arr = this.term.split('–');
          if (item_arr[1] !== '') {
            item_arr[0] = item_arr[0].trim().replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
            item_arr[1] = item_arr[1].trim().replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
            if (item.label !== `${item_arr[0]} – `) {
              if (item.img) {
                return $(`<li title="${item.value}"></li>`)
                  .data('item.autocomplete', item)
                  .append(`<a><div class="cover album_img img40" style="background-image: url(${item.image_server_protocol}${item.image_server_ip}/${item.img})"></div>${String(item.label).replace(new RegExp(`${item_arr[0]}|${item_arr[1]}|–`, 'gi'), '<span class="highlight">$&</span>')}</a>`)
                  .appendTo(ul);
              } else {
                return $(`<li title="${item.value}"></li>`)
                  .data('item.autocomplete', item)
                  .append(`<a><span class="no_img">${String(item.label).replace(new RegExp(`${item_arr[0]}|${item_arr[1]}|–`, 'gi'), '<span class="highlight">$&</span>')}</span></a>`)
                  .appendTo(ul);
              }
            } else {
              $('#addListeningText').attr('data-placeholder', `${this.term} (yyyy)`);
              return $('<li></li>');
            }
          } else {
            item_arr[0] = item_arr[0]
              .trim()
              .replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&')
              .replace(/<\/?[^>]+(>|$)/g, '');
            if (item.img) {
              return $(`<li title="${item.value}"></li>`)
                .data('item.autocomplete', item)
                .append(`<a><div class="cover album_img img40" style="background-image: url(${item.image_server_protocol}${item.image_server_ip}/${item.img})"></div>${String(item.label).replace(new RegExp(`${item_arr[0]}|–`, 'gi'), '<span class="highlight">$&</span>')}</a>`)
                .appendTo(ul);
            } else {
              return $(`<li title="${item.value}"></li>`)
                .data('item.autocomplete', item)
                .append(`<a><span class="no_img">${String(item.label).replace(new RegExp(`${item_arr[0]}|–`, 'gi'), '<span class="highlight">$&</span>')}</span></a>`)
                .appendTo(ul);
            }
          }
        } else {
          this.term = this.term
            .trim()
            .replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&')
            .replace(/<\/?[^>]+(>|$)/g, '');
          if (item.img) {
            return $(`<li title="${item.value}"></li>`)
              .data('item.autocomplete', item)
              .append(`<a><div class="cover album_img img40" style="background-image: url(${item.image_server_protocol}${item.image_server_ip}/${item.img})"></div>${String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>')}</a>`)
              .appendTo(ul);
          } else {
            return $(`<li title="${item.value}"></li>`)
              .data('item.autocomplete', item)
              .append(`<a><span class="no_img">${String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>')}</span></a>`)
              .appendTo(ul);
          }
        }
      }
    };
  },
  // select: function (event, ui) {
  //   event.preventDefault();
  //   if (ui.item.value != 'label') {
  //     return;
  //   }
  // },
  initMouseTrap: () => {
    Mousetrap.bind(['mod+k'], _e => {
      window.location = '/';
    });
    Mousetrap.bind(['mod+shift+s'], _e => {
      $('.search_text').focus();
    });
  },
  initTooltips: () => {
    // The .tooltip elements are all <img>, a replaced element that can't
    // render ::after generated content per the CSS spec, so the bubble is
    // triggered from the wrapping <label> instead. title moves there too
    // (off the img entirely) so the browser's native title tooltip doesn't
    // also fire alongside the CSS one.
    document.querySelectorAll('.tooltip[title]').forEach(el => {
      var wrapper = el.closest('label') || el.parentElement;
      wrapper.classList.add('tooltip');
      wrapper.dataset.tooltip = el.getAttribute('title');
      el.removeAttribute('title');
      el.classList.remove('tooltip');
    });
  },
  initStatsterEvents: () => {
    $('.search_text').autocomplete({
      html: true,
      minLength: 3,
      response: function () {
        $(this).removeClass('working');
      },
      select: (_event, ui) => {
        if (ui.item.url !== undefined) {
          window.location = ui.item.url;
        }
      },
      search: function () {
        $(this).addClass('working');
      },
      source: '/api/search/get/10/',
      open: function () {
        var self = $(this).data('ui-autocomplete');

        // Only override if not already done
        if (!self._originalClose) {
          self._originalClose = self.close;
          self.close = function (event) {
            // Prevent closing when blur is triggered by virtual keyboard hiding
            if (event?.originalEvent && event.originalEvent.type === 'blur') {
              return;
            }
            this._originalClose.call(this, event);
          };
        }
      }
    });
    $('.search_text').keyup(function () {
      $(this).val() !== '' ? $('.search_submit').prop('disabled', false) : $('.search_submit').prop('disabled', true);
    });
    $('.settings a').click(function () {
      $(this).parent('.settings').find('a').addClass('unactive');
      $(this).removeClass('unactive');
    });
    $('.user_container')
      .click(function () {
        var sub_nav = $(this).parent().find('ul.subnav');
        if (sub_nav.is(':visible')) {
          $(this).removeClass('active');
          sub_nav.slideUp('fast');
        } else {
          $(this).addClass('active');
          sub_nav.slideDown('fast').show();
          $(this)
            .parent()
            .hover(
              () => {},
              () => {
                // sub_nav.slideUp('slow');
              }
            );
        }
      })
      .hover(
        function () {
          $(this).addClass('subhover');
        },
        function () {
          $(this).removeClass('subhover');
        }
      );
    $('.toggle_username').click(function () {
      if ($(this).hasClass('active')) {
        $.ajax({
          dataType: 'json',
          statusCode: {
            200: () => {
              // 200 OK
              location.reload();
            }
          },
          type: 'GET',
          url: '/Ajax/selectYourself/delete'
        });
      } else {
        $.ajax({
          dataType: 'json',
          statusCode: {
            200: () => {
              // 200 OK
              location.reload();
            }
          },
          type: 'GET',
          url: '/Ajax/selectYourself/add'
        });
      }
    });
    $(window).scroll(() => {
      if ($(window).scrollTop() > 5) {
        if ($('#headingCont').length !== 0) {
          $('#topCont').addClass('scrolled');
        }
      } else {
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
    $('html').on('mouseover', '.music_wall li .meta, .music_list li .meta', event => {
      event.stopPropagation();
    });
    $('html').on('mouseout', '.music_wall li .meta, .music_list li .meta', event => {
      event.stopPropagation();
    });
    $('html').on('click', '.some_link', function () {
      var specs = `top=${screen.height / 2 - 420 / 2},left=${screen.width / 2 - 550 / 2},toolbar=0,status=0,width=550,height=420`;
      window.open($(this).data('url') + window.location.href, 'Share', specs);
    });
  }
};
var view = {};

$.extend(view, {});
app.highlightPatch();
app.initMouseTrap();
app.initStatsterEvents();
app.initTooltips();

if ($('#headingCont').length === 0) {
  $('#topCont').addClass('scrolled');
}
