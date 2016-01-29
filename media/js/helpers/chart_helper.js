$.extend(view, {
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
          y:17,
          style:{
            color:'#444',
            fontFamily:'Raleway',
            fontSize:14,
            fontWeight:'normal'
          },
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
            fontFamily:'Raleway',
            fontSize:14,
            fontWeight:'normal'
          }
        },
        title:{
          enabled:false
        }
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
          color:'#444',
          fontFamily:'Raleway',
          fontSize:14,
          fontWeight:'normal'
        }
      },
      plotOptions:{
        column:{
          color:'rgba(182, 192, 191, 0.5)',
          groupPadding:0.01,
          pointPadding:0.06
        }
      },
      series: [{
        data:[],
        type:'column'
      }]
    }).highcharts();
  }
});