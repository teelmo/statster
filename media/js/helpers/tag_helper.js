Object.assign(view, {
  populateTagsMenu: (type, order_by) =>
    ajax({
      data: {
        limit: 1000,
        lower_limit: '1970-00-00',
        order_by: order_by,
        username: `<?=!empty($_GET['u']) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          var optgroup = document.querySelector(`#${type}`);
          data.forEach(value => {
            var option = document.createElement('option');
            option.className = type;
            option.value = `${type}:${value.tag_id}`;
            option.textContent = value.name;
            optgroup.append(option);
          });
        }
      },
      url: `/api/${type}/get/all`,
      type: 'GET'
    }),
  initTagHelperEvents: () => {
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#addtags');
      if (!target) {
        return;
      }
      var tagAdd = document.querySelector('#tagAdd');
      tagAdd.classList.toggle('hidden');
      tagAdd.classList.toggle('active');
      var searchInput = document.querySelector('#tagAdd .searchable_select_input');
      if (!tagAdd.classList.contains('hidden') && searchInput) {
        searchInput.focus();
      }
    });
  }
});

view.initTagHelperEvents();
Promise.all([view.populateTagsMenu('genre', 'name'), view.populateTagsMenu('keyword', 'name'), view.populateTagsMenu('nationality', 'country')]).then(() => {
  initSearchableSelect(document.querySelector('#tagAdd select'));
});
