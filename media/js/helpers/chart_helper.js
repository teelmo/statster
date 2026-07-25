Object.assign(view, {
  initChart: () => {
    // The bundled Highcharts (4.1.9) predates the Highcharts.chart(el, opts)
    // convenience factory (added in 4.2.5), so this uses the constructor
    // form with chart.renderTo instead - still Highcharts' own native API,
    // not a jQuery dependency.
    app.chart = new Highcharts.Chart({
      chart: {
        backgroundColor: 'transparent',
        height: 300,
        renderTo: document.querySelector('.music_bar'),
        zoomType: 'x'
      },
      credits: {
        enabled: false
      },
      legend: {
        enabled: false
      },
      plotOptions: {
        column: {
          borderWidth: 0,
          color: getComputedStyle(document.documentElement).getPropertyValue('--theme').trim() === 'dark' ? '#FFE082' : '#FFA000',
          groupPadding: 0.01,
          maxPointWidth: 100,
          pointPadding: 0.06
        }
      },
      series: [
        {
          data: [],
          type: 'column'
        }
      ],
      title: {
        text: null
      },
      tooltip: {
        backgroundColor: '#fff',
        borderColor: '#ccc',
        borderRadius: 0,
        borderWidth: 1,
        formatter: function () {
          return app.formatNr(this.y);
        },
        shadow: false,
        style: {
          color: '#999',
          fontFamily: 'IBM Plex Mono',
          fontSize: '14px',
          fontWeight: '300'
        }
      },
      xAxis: {
        allowDecimals: false,
        labels: {
          style: {
            color: '#999',
            fontFamily: 'IBM Plex Mono',
            fontSize: '12px',
            fontWeight: '300'
          },
          y: 14
        },
        lineWidth: 0,
        minorGridLineWidth: 0,
        minorTickLength: 0,
        tickLength: 0,
        title: {
          enabled: false
        }
      },
      yAxis: {
        allowDecimals: false,
        labels: {
          formatter: function () {
            return app.formatNr(this.value);
          },
          style: {
            color: '#999',
            fontFamily: 'IBM Plex Mono',
            fontSize: '12px',
            fontWeight: '300'
          }
        },
        gridLineColor: 'rgba(170, 170, 170, 0.5)',
        gridLineDashStyle: 'Dash',
        gridLineWidth: 0.5,
        title: {
          enabled: false
        }
      }
    });
  },
  // Renders a minimal sparkline (line only, no fill) as an inline SVG, mirroring
  // the one peity('line', {...}) call this replaces. The generated <svg> keeps
  // peity's own "peity" class so the existing `svg.peity polyline` CSS rule
  // (base.css) still colors the stroke - no styling lost by dropping the library.
  renderSparkline: (el, values, opts) => {
    var width = opts.width;
    var height = opts.height;
    var strokeWidth = opts.strokeWidth;
    var min = opts.min !== null && opts.min !== undefined ? opts.min : Math.min(...values);
    var max = opts.max !== null && opts.max !== undefined ? opts.max : Math.max(...values);
    var range = max - min || 1;
    var stepX = values.length > 1 ? width / (values.length - 1) : width;
    var points = values
      .map((value, i) => {
        var x = i * stepX;
        var y = strokeWidth / 2 + (height - strokeWidth) * (1 - (value - min) / range);
        return `${x},${y}`;
      })
      .join(' ');

    var svgNS = 'http://www.w3.org/2000/svg';
    var svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('class', 'peity');
    svg.setAttribute('width', width);
    svg.setAttribute('height', height);
    svg.style.display = 'none';

    var polyline = document.createElementNS(svgNS, 'polyline');
    polyline.setAttribute('points', points);
    polyline.setAttribute('fill', 'none');
    polyline.setAttribute('stroke-width', strokeWidth);
    svg.appendChild(polyline);

    el.style.display = 'none';
    el.insertAdjacentElement('afterend', svg);
    // Note: jQuery's fadeIn(1000) animated this; plain display toggle drops
    // the animation but keeps the same end state.
    svg.style.display = '';
  },
  initGraph: data => {
    if (data[data.length - 1].cumulative_count !== '1') {
      const chart_data = [0];
      data.forEach(value => {
        if (value.cumulative_count !== 0 && value.cumulative_count - data[0].cumulative_count > 0) {
          chart_data.push(value.cumulative_count - data[0].cumulative_count);
        }
      });
      if (chart_data.length > 2) {
        document.querySelectorAll('.line').forEach(el => {
          view.renderSparkline(el, chart_data, {
            height: 18,
            max: null,
            min: 0,
            strokeWidth: 2,
            width: 100
          });
        });
      }
    }
  }
});
