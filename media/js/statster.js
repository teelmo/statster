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
  initMouseTrap: () => {
    Mousetrap.bind(['mod+k'], _e => {
      window.location = '/';
    });
    Mousetrap.bind(['mod+shift+s'], _e => {
      document.querySelector('.search_text').focus();
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
    document.querySelectorAll('.search_text').forEach(el => {
      initAutocomplete(el, {
        dropdownId: 'ui-id-1',
        minLength: 3,
        source: '/api/search/get/10/',
        onSelect: item => {
          if (item.url !== undefined) {
            window.location = item.url;
          }
        }
      });
      el.addEventListener('keyup', () => {
        document.querySelectorAll('.search_submit').forEach(btn => {
          btn.disabled = el.value === '';
        });
      });
    });
    document.querySelectorAll('.settings a').forEach(el => {
      el.addEventListener('click', function () {
        this.parentElement.querySelectorAll('a').forEach(sibling => {
          sibling.classList.add('unactive');
        });
        this.classList.remove('unactive');
      });
    });
    document.querySelectorAll('.user_container').forEach(el => {
      el.addEventListener('click', function () {
        var subNav = this.parentElement.querySelector('ul.subnav');
        if (!subNav) {
          return;
        }
        var isOpen = subNav.style.display !== 'none';
        this.classList.toggle('active', !isOpen);
        subNav.style.display = isOpen ? 'none' : 'block';
      });
    });
    document.addEventListener('click', event => {
      document.querySelectorAll('.user_container').forEach(el => {
        var subNav = el.querySelector('ul.subnav');
        if (!subNav || subNav.style.display === 'none' || el.contains(event.target)) {
          return;
        }
        el.classList.remove('active');
        subNav.style.display = 'none';
      });
    });
    document.querySelectorAll('.toggle_username').forEach(el => {
      el.addEventListener('click', function () {
        ajax({
          dataType: 'json',
          statusCode: {
            200: () => {
              // 200 OK
              location.reload();
            }
          },
          type: 'GET',
          url: this.classList.contains('active') ? '/Ajax/selectYourself/delete' : '/Ajax/selectYourself/add'
        });
      });
    });
    window.addEventListener('scroll', () => {
      var topCont = document.querySelector('#topCont');
      if (!document.querySelector('#headingCont') || !topCont) {
        return;
      }
      topCont.classList.toggle('scrolled', window.scrollY > 5);
    });
    document.querySelector('html').addEventListener('mouseover', event => {
      var meta = event.target.closest('.music_wall li .meta, .music_list li .meta');
      if (meta) {
        event.stopPropagation();
        return;
      }
      var li = event.target.closest('.music_wall li, .music_list li');
      if (li) {
        li.classList.add('hover');
        event.stopPropagation();
      }
    });
    document.querySelector('html').addEventListener('mouseout', event => {
      var meta = event.target.closest('.music_wall li .meta, .music_list li .meta');
      if (meta) {
        event.stopPropagation();
        return;
      }
      var li = event.target.closest('.music_wall li, .music_list li');
      if (li) {
        li.classList.remove('hover');
        event.stopPropagation();
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('.some_link');
      if (!target) {
        return;
      }
      var specs = `top=${screen.height / 2 - 420 / 2},left=${screen.width / 2 - 550 / 2},toolbar=0,status=0,width=550,height=420`;
      window.open(target.dataset.url + window.location.href, 'Share', specs);
    });
  }
};
var view = {};

app.initMouseTrap();
app.initStatsterEvents();
app.initTooltips();

if (document.querySelector('#headingCont') === null) {
  var topCont = document.querySelector('#topCont');
  if (topCont) {
    topCont.classList.add('scrolled');
  }
}
