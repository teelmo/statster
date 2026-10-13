Object.assign(view, {
  initChart: () => {
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
  // (widget_controls.css) still colors the stroke - no styling lost by dropping the library.
  // `points` is [{x, y}, ...] ordered by x ascending - x is spaced by its own
  // value (e.g. elapsed time), not by array index, so a gap between points reads
  // as a gap on the line instead of being spread evenly like every other segment.
  renderSparkline: (el, points, opts) => {
    var width = opts.width;
    var height = opts.height;
    var strokeWidth = opts.strokeWidth;
    var values = points.map(point => point.y);
    var min = opts.min !== null && opts.min !== undefined ? opts.min : Math.min(...values);
    var max = opts.max !== null && opts.max !== undefined ? opts.max : Math.max(...values);
    var range = max - min || 1;
    var minX = points[0].x;
    var maxX = points[points.length - 1].x;
    var rangeX = maxX - minX || 1;
    var polylinePoints = points
      .map(point => {
        var x = points.length > 1 ? ((point.x - minX) / rangeX) * width : width / 2;
        var y = strokeWidth / 2 + (height - strokeWidth) * (1 - (point.y - min) / range);
        return `${x},${y}`;
      })
      .join(' ');

    var svgNS = 'http://www.w3.org/2000/svg';
    var svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('class', 'peity');
    svg.setAttribute('width', width);
    svg.setAttribute('height', height);

    var polyline = document.createElementNS(svgNS, 'polyline');
    polyline.setAttribute('points', polylinePoints);
    polyline.setAttribute('fill', 'none');
    polyline.setAttribute('stroke-width', strokeWidth);
    svg.appendChild(polyline);

    el.classList.add('hidden');
    el.insertAdjacentElement('afterend', svg);
    // Mirrors the old peity's jQuery fadeIn(1000): the "visible" class carries
    // the CSS opacity transition (widget_controls.css), added on the next tick so the
    // browser has painted the initial opacity:0 state before it starts.
    // setTimeout, not requestAnimationFrame - rAF is paused entirely for
    // backgrounded/hidden tabs, which would leave the sparkline invisible
    // for as long as the tab stays unfocused.
    setTimeout(() => {
      svg.classList.add('visible');
    }, 0);
  },
  initGraph: data => {
    if (data[data.length - 1].cumulative_count !== '1') {
      // line_date is always a 'YYYYMM' bucket (see getListeningsCumulative /
      // getTagsCumulative) - turn it into a month index so points can be spaced
      // by actual elapsed time instead of by array position.
      var monthIndex = line_date => parseInt(line_date.substring(0, 4), 10) * 12 + (parseInt(line_date.substring(4, 6), 10) - 1);
      var baseMonth = monthIndex(data[0].line_date);
      const points = [{ x: 0, y: 0 }];
      data.forEach(value => {
        if (value.cumulative_count !== 0 && value.cumulative_count - data[0].cumulative_count > 0) {
          points.push({
            x: monthIndex(value.line_date) - baseMonth,
            y: value.cumulative_count - data[0].cumulative_count
          });
        }
      });
      if (points.length > 2) {
        document.querySelectorAll('.line').forEach(el => {
          view.renderSparkline(el, points, {
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
