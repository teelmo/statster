$.extend(view, {
  populateTagsMenu: function (type, order_by) {
    return $.ajax({
      data:{
        limit:1000,
        lower_limit:'1970-00-00',
        order_by:order_by,
        username:'<?=!empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.each(data, function (i, value) {
            $('<option class="' + type + '" value="' + type + ':' + value['tag_id'] + '">' + value['name'] + '</option>').appendTo($('#' + type));
          });
        }
      },
      url:'/api/' + type + '/get/all',
      type:'GET'
    });
  },
  initTagHelperEvents: function () {
    $('html').on('click', '#addtags', function () {
      if ($('#tagAdd').is(':visible')) {
        $('#tagAdd').css('display', 'none');
      }
      else {
        $('#tagAdd').css('display', 'inline');
      }
      $('.search-field input[type="text"]').focus();
    });
  }
});

function prioritizeOptions($select, searchTerm) {
  var startsWithMatches = [];
  var containsMatches = [];

  $select.find('option').each(function() {
    var text = $(this).text().toLowerCase();
    if (text.startsWith(searchTerm)) {
      startsWithMatches.push(this);
    } else if (text.indexOf(searchTerm) !== -1) {
      containsMatches.push(this);
    }
  });

  var sortedOptions = startsWithMatches.concat(containsMatches);

  // Only if searchTerm is not empty, otherwise no need to reorder
  if (searchTerm.length) {
    $select.html('').append(sortedOptions);
  }
}

$(document).ready(function () {
  view.initTagHelperEvents();
  $.when(
    view.populateTagsMenu('genre', 'name'),
    view.populateTagsMenu('keyword', 'name'),
    view.populateTagsMenu('nationality', 'country')
  ).done(function () {
    $(document).one('ajaxStop', function (event, request, settings) {
      var $select = $('#tagAdd select');
      // Initialize Chosen first
      $select.chosen({});
      // Now attach the prioritized search functionality
      $select.prioritizedChosenSearch();
    });
  });
});
