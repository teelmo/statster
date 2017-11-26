$.extend(view, {
  initChart: function () {
    app.chart = $('.music_bar').highcharts({
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
            color:'#999',
            fontFamily:'Raleway',
            fontSize:'14px',
            fontWeight:'300'
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
            color:'#999',
            fontFamily:'Raleway',
            fontSize:'14px',
            fontWeight:'300'
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
          color:'#999',
          fontFamily:'Raleway',
          fontSize:'14px',
          fontWeight:'300'
        }
      },
      plotOptions:{
        column:{
          color:'rgba(182, 192, 191, 0.5)',
          groupPadding:0.01,
          pointPadding:0.06,
          pointWidth:100
        }
      },
      series: [{

        data:[],
        type:'column'
      }]
    }).highcharts();
  }
});