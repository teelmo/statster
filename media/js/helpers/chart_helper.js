$.extend(view, {
  initChart: function () {
    app.chart = $('.music_bar').highcharts({
      chart:{
        backgroundColor:'transparent',
        height:300,
        zoomType:'x'
      },
      credits:{
        enabled:false
      },
      legend:{
        enabled:false,
      },
      plotOptions:{
        column:{
          borderWidth: 0,
          color:(getComputedStyle(document.documentElement).getPropertyValue('--theme').trim() === 'dark') ? '#FFE082' : '#FFA000',
          groupPadding:0.01,
          maxPointWidth:100,
          pointPadding:0.06
        }
      },
      series: [{
        data:[],
        type:'column'
      }],
      title:{
        text:null
      },
      tooltip:{
        backgroundColor:'#fff',
        borderColor:'#ccc',
        borderRadius:0,
        borderWidth:1,
        formatter: function () {
          return app.formatNr(this.y);
        },
        shadow:false,
        style:{
          color:'#999',
          fontFamily:'IBM Plex Mono',
          fontSize:'14px',
          fontWeight:'300'
        }
      },
      xAxis:{
        allowDecimals:false,
        labels:{
          style:{
            color:'#999',
            fontFamily:'IBM Plex Mono',
            fontSize:'12px',
            fontWeight:'300'
          },
          y:14
        },
        lineWidth: 0,
        minorGridLineWidth: 0,
        minorTickLength: 0,
        tickLength: 0,
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
            color:'#999',
            fontFamily:'IBM Plex Mono',
            fontSize:'12px',
            fontWeight:'300'
          }
        },
        gridLineColor:'rgba(170, 170, 170, 0.5)',
        gridLineDashStyle:'Dash',
        gridLineWidth:0.5,
        title:{
          enabled:false
        }
      }
    }).highcharts();
  },
  initGraph: function (data) {
    if (data[data.length - 1].cumulative_count !== '1') {
      var chart_data = [0];
      $.each(data, function (i, value) {
        if (value.cumulative_count !== 0 && (value.cumulative_count - data[0].cumulative_count > 0)) {
          chart_data.push(value.cumulative_count - data[0].cumulative_count);
        }
      });
      if (chart_data.length > 2) {
        $('.line').text(chart_data.slice(',')).peity('line', {
          delimiter: ',',
          fill: '',
          height: 18,
          max: null,
          min: 0,
          strokeWidth: 2,
          width: 100
        }).next('svg').hide().fadeIn(1000);
      }
    }
  }
});