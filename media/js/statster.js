var app = {
  highlightPatch: function () {
    $.ui.autocomplete.prototype._renderItem = function(ul, item) {
      var t = String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>');
      return $('<li></li>').data('item.autocomplete', item).append('<a>' + t + '</a>').appendTo(ul);
    }
  }
}

$(document).ready(function() {
  $('#searchString').autocomplete({
    minLength:3,html:true,source:'/autoComplete/search',
    search: function() {
      $(this).addClass('working');
    },
    open: function() {
      $(this).removeClass('working');
    }
  });
});