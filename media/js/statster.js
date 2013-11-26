if (document.images) {
  preLoadImg1 = new Image();
  preLoadImg1.src = '/media/img/ajax-loader-bar.gif';
  preLoadImg2 = new Image();
  preLoadImg2.src = '/media/img/ajax-loader-circle.gif';
}
Mousetrap.bind(['mod+k'], function(e) {
  window.location = '/';
});
Mousetrap.bind(['mod+shift+s'], function(e) {
  $('#searchString').focus();
});

var app = {
  highlightPatch: function() {
    $.ui.autocomplete.prototype._renderItem = function(ul, item) {
      if (item.value == '') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(item.label).appendTo(ul);
      }
      else {
        return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
      }
    }
  },
  select: function(event, ui) {
    event.preventDefault();
    if (ui.item.value != 'label') {
      return;
    }
  }
}

$(document).ready(function() {
  $('#searchString').autocomplete({
    minLength:3,html:true,source:'/autoComplete/search',
    select: function(event, ui) {
      console.log(ui.item.url)
      window.location = ui.item.url;
    },
    search: function() {
      $(this).addClass('working');
    },
    open: function() {
      $(this).removeClass('working');
    }
  });
  
  app.highlightPatch();
});