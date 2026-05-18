$.extend(view, {
  populateTagsMenu: (type, order_by) =>
    $.ajax({
      data: {
        limit: 1000,
        lower_limit: '1970-00-00',
        order_by: order_by,
        username: `<?=!empty($_GET['u']) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.each(data, (_i, value) => {
            $(`<option class="${type}" value="${type}:${value.tag_id}">${value.name}</option>`).appendTo($(`#${type}`));
          });
        }
      },
      url: `/api/${type}/get/all`,
      type: 'GET'
    }),
  initTagHelperEvents: () => {
    $('html').on('click', '#addtags', () => {
      if ($('#tagAdd').is(':visible')) {
        $('#tagAdd').css('display', 'none');
      } else {
        $('#tagAdd').css('display', 'inline');
      }
      $('.search-field input[type="text"]').focus();
    });
  }
});

// function prioritizeOptions($select, searchTerm) {
//   var startsWithMatches = [];
//   var containsMatches = [];

//   $select.find('option').each(function () {
//     var text = $(this).text().toLowerCase();
//     if (text.startsWith(searchTerm)) {
//       startsWithMatches.push(this);
//     } else if (text.indexOf(searchTerm) !== -1) {
//       containsMatches.push(this);
//     }
//   });

//   var sortedOptions = startsWithMatches.concat(containsMatches);

//   // Only if searchTerm is not empty, otherwise no need to reorder
//   if (searchTerm.length) {
//     $select.html('').append(sortedOptions);
//   }
// }

$(document).ready(() => {
  view.initTagHelperEvents();
  $.when(view.populateTagsMenu('genre', 'name'), view.populateTagsMenu('keyword', 'name'), view.populateTagsMenu('nationality', 'country')).done(() => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      var $select = $('#tagAdd select');
      // Initialize Chosen first
      $select.chosen({});
      // Now attach the prioritized search functionality
      $select.prioritizedChosenSearch();
    });
  });
});
