document.querySelectorAll('.func_container .value').forEach(el => {
  el.addEventListener('click', function () {
    var subNav = this.parentElement.querySelector('ul.subnav');
    if (!subNav) {
      return;
    }
    // Note: jQuery's slideUp()/slideDown() aren't used here (just show()/
    // hide()), so this is a direct display-toggle port, no animation lost.
    if (subNav.offsetParent !== null) {
      this.classList.remove('active');
      subNav.style.display = 'none';
    } else {
      document.querySelectorAll('.func_container .subnav').forEach(sn => {
        sn.style.display = 'none';
      });
      document.querySelectorAll('.func_container .value').forEach(v => {
        v.classList.remove('active');
      });
      this.classList.add('active');
      // ul.subnav's own CSS class sets display: none (no separate "hidden"
      // utility class involved), so clearing to '' can't override it - an
      // explicit display value is required, same as jQuery's .show() would
      // have computed for this <ul>.
      subNav.style.display = 'block';
    }
  });
  el.addEventListener('mouseenter', function () {
    this.classList.add('subhover');
  });
  el.addEventListener('mouseleave', function () {
    this.classList.remove('subhover');
  });
});
document.querySelectorAll('.func_container .subnav li').forEach(li => {
  li.addEventListener('click', function () {
    document.querySelectorAll('.func_container .subnav').forEach(sn => {
      sn.style.display = 'none';
    });
    document.querySelectorAll('.func_container .value').forEach(v => {
      v.classList.remove('active');
    });
    var parentUl = this.parentElement;
    var loader = document.querySelector(`#${parentUl.dataset.loader}`);
    if (loader) {
      loader.style.display = '';
      loader.classList.remove('hidden');
    }
    var name = parentUl.dataset.name;
    var callback = parentUl.dataset.callback;
    var value = this.dataset.value;
    var funcContainer = parentUl.parentElement;
    var valueEl = funcContainer.querySelector('.value');
    valueEl.textContent = this.textContent;
    valueEl.dataset.value = value;
    ajax({
      data: {
        name: name,
        value: value
      },
      dataType: 'json',
      statusCode: {
        204: () => {
          // 204 No Content
          view[callback](value);
        },
        400: () => {
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/user/update/interval/'
    });
  });
});
