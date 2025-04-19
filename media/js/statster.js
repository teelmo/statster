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
  substrWords: function (text, maxChar = 35, end = '…') {
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
  setOverlayBackground: function (image) {
    document.querySelector('.background_overlay').style.backgroundImage = 'url(' + image + ')';
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
        if (this.term.indexOf('–') !== -1) {
          var item_arr = this.term.split('–');
          if (item_arr[1] != '') {
            item_arr[0] = item_arr[0].trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            item_arr[1] = item_arr[1].trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            if (item.label != item_arr[0] + ' – ') {
              if (item.img) {
                return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.image_server_protocol + item.image_server_ip + '/' + item.img + ')"></div>' + String(item.label).replace(new RegExp(item_arr[0] + '|' + item_arr[1] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
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
              return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.image_server_protocol + item.image_server_ip + '/' + item.img + ')"></div>' + String(item.label).replace(new RegExp(item_arr[0] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
            }
            else {
              return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><span class="no_img">' + String(item.label).replace(new RegExp(item_arr[0] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</span></a>').appendTo(ul);
            }
          }
        }
        else {
          this.term = this.term.trim().replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&").replace(/<\/?[^>]+(>|$)/g, '');
          if (item.img) {
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a><div class="cover album_img img40" style="background-image: url(' + item.image_server_protocol + item.image_server_ip + '/' + item.img + ')"></div>' + String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
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
      html: true,
      minLength: 3,
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
      source: '/api/search/get/10/',
      open: function () {
        var self = $(this).data('ui-autocomplete');

        // Only override if not already done
        if (!self._originalClose) {
          self._originalClose = self.close;
          self.close = function (event) {
            // Prevent closing when blur is triggered by virtual keyboard hiding
            if (event && event.originalEvent && event.originalEvent.type === 'blur') {
              return;
            }
            this._originalClose.call(this, event);
          };
        }
      }
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
  app.highlightPatch();
  app.initMouseTrap();
  app.initStatsterEvents();
  app.initToolTipster();

  if ($('#headingCont').length === 0) {
    $('#topCont').addClass('scrolled');
  }

  function waitForChosenThen($select, callback, maxAttempts = 20, attempt = 0) {
    var chosenInstance = $select.data('chosen');
    if (chosenInstance) {
      callback($select, chosenInstance);
    } else if (attempt < maxAttempts) {
      setTimeout(function () {
        waitForChosenThen($select, callback, maxAttempts, attempt + 1);
      }, 100);
    } else {
      console.warn('Chosen was not initialized on the element in time.');
    }
  }

  (function ($) {
    $.fn.prioritizedChosenSearch = function () {
      return this.each(function () {
        var $select = $(this);

        waitForChosenThen($select, function ($select, chosenInstance) {
          var $searchInput = chosenInstance.search_field;

          $searchInput.on('keyup', function (e) {
            // Ignore navigation keys
            if ([13, 27, 38, 40, 37, 39].includes(e.which)) return;

            var searchTerm = ($searchInput.val() || '').toLowerCase();
            var selectedValues = $select.val();

            var $optgroups = $select.find('optgroup');

            if ($optgroups.length) {
              // Sort options inside each optgroup
              $optgroups.each(function () {
                var $optgroup = $(this);
                var options = $optgroup.find('option').get();

                options.sort(function (a, b) {
                  return compareOptionTexts(a, b, searchTerm);
                });

                $optgroup.empty().append(options);
              });
            } else {
              // No optgroups — sort all options
              var options = $select.find('option').get();

              options.sort(function (a, b) {
                return compareOptionTexts(a, b, searchTerm);
              });

              $select.empty().append(options);
            }

            $select.val(selectedValues);
            $select.trigger('chosen:updated');

            chosenInstance.search_field.val(searchTerm);
            chosenInstance.winnow_results();
          });
        });
      });
    };

    function compareOptionTexts(a, b, searchTerm) {
      var aText = $(a).text().toLowerCase();
      var bText = $(b).text().toLowerCase();

      var aStarts = aText.startsWith(searchTerm) ? 0 : (aText.includes(searchTerm) ? 1 : 2);
      var bStarts = bText.startsWith(searchTerm) ? 0 : (bText.includes(searchTerm) ? 1 : 2);

      if (aStarts !== bStarts) return aStarts - bStarts;
      return aText.localeCompare(bText);
    }

  })(jQuery);
});