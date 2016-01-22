var app = {
  highlightPatch: function () {
    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
      if (item.value == '') {
        return $('<li></li>').addClass('header').data('item.autocomplete', item).append(item.label).appendTo(ul);
      }
      else {
        if (this.term.indexOf('–') !== -1) {
          var item_arr = this.term.split('–');
          if (item_arr[1] != '') {
            item_arr[0] = item_arr[0].trim();
            item_arr[1] = item_arr[1].trim();
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(item_arr[0].replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '|' + item_arr[1] + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
          }
          else {
            item_arr[0] = item_arr[0].trim();
            return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(item_arr[0].replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '|–', 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);

          }
        }
        else {
          this.term = this.term.trim();
          return $('<li title="' + item.value + '"></li>').data('item.autocomplete', item).append('<a>' + item.img + String(item.label).replace(new RegExp(this.term.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), 'gi'), '<span class="highlight">$&</span>') + '</a>').appendTo(ul);
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
      $('#searchString').focus();
    });
  },
  compareStrings: function (a, b) {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  initChart: function () {
    app.chart = $('.bar_chart').highcharts({
      chart:{
        height:300,
        zoomType:'x'
      },
      credits:{
        enabled:false
      },
      title:{
        text:null
      },
      legend:{
        enabled:false,
      },
      xAxis:{
        allowDecimals:false,
        labels:{
          rotation:0,
          y:17,
          style:{
            color:'#444',
            fontFamily:'Open Sans',
            fontSize:14
          },
        },
        labels:{
          style:{
            fontWeight:'normal',
            fontSize:14,
            fontFamily:'Open Sans',
            color:'#444'
          }
        },
        title:{
          enabled:false
        }
      },
      yAxis:{
        allowDecimals:false,
        labels:{
          formatter: function () {
            return app.formatNr(this.value);
          },
          style:{
            color:'#444',
            fontFamily:'Open Sans',
            fontSize:14,
            fontWeight:'normal'
          }
        },
        title:{
          enabled:false
        }
      },
      tooltip:{
        formatter: function () {
          return app.formatNr(this.y);
        },
        backgroundColor:'#fff',
        borderColor:'#ccc',
        borderRadius:0,
        borderWidth:1,
        shadow:false,
        style:{
          color:'#444',
          fontFamily:'Open Sans',
          fontSize:14,
          fontWeight:'bold'
        }
      },
      plotOptions:{
        column:{
          groupPadding:0.01,
          color:'rgba(182, 192, 191, 0.5)'
        }
      },
      series: [{
        data:[],
        type:'column'
      }]
    }).highcharts();
  },
  formatNr: function (x) {
    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return (x == '') ? 0 : x;
  },
  initStatsterEvents: function () {
    $('.search_text').autocomplete({
      minLength:3,
      html:true,
      source:'/autoComplete/search',
      select: function (event, ui) {
        window.location = ui.item.url;
      },
      search: function () {
        $(this).addClass('working');
      },
      open: function () {
        $(this).removeClass('working');
      }
    });
    $('.settings a').click(function () {
      $(this).parent('.settings').find('a').addClass('unactive');
      $(this).removeClass('unactive');
    });
    $('.toggle_username').click(function () {
      
    });
  }
}
var view = {}

$(document).ready(function () {
  if (document.images) {
    preLoadImg1 = new Image();
    preLoadImg1.src = '/media/img/ajax-loader-bar.gif';
    preLoadImg2 = new Image();
    preLoadImg2.src = '/media/img/ajax-loader-circle.gif';
  }

  app.highlightPatch();
  app.initMouseTrap();
  app.initStatsterEvents();
});