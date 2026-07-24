// media/js/ajax.js
// fetch()-based replacement for $.ajax(), shaped to match its call signature
// (data/type/dataType/statusCode) so call sites need minimal changes. Static
// file, no PHP interpolation - loaded as a real <script defer> in header.php
// rather than spliced through footer.php like the page-specific JS files.
//
// ajax.pending replaces $.active; there's no ajaxStop equivalent - callers
// that used $(document).one('ajaxStop', fn) should use Promise.all([...])
// on the specific requests they're waiting on instead.

function ajax(opts) {
  var url = opts.url;
  var type = (opts.type || 'GET').toUpperCase();
  var data = opts.data;
  var statusCode = opts.statusCode || {};
  // The backend echoes json_encode() without setting Content-Type: application/json
  // (defaults to text/html), so - like $.ajax's dataType option - the caller has to
  // say up front whether to parse the body as JSON; the response headers can't tell us.
  var isJson = opts.dataType === 'json';
  var isGet = type === 'GET';
  var headers = { 'X-Requested-With': 'XMLHttpRequest' };
  var body;

  var qs = data ? ajaxSerialize(data) : '';
  if (isGet) {
    if (qs) {
      url += (url.indexOf('?') === -1 ? '?' : '&') + qs;
    }
  } else if (data !== undefined) {
    body = qs;
    headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
  }

  ajax.pending++;
  return fetch(url, { method: type, headers: headers, body: body })
    .then(response => {
      return response.text().then(text => {
        var payload = text;
        if (isJson && text) {
          try {
            payload = JSON.parse(text);
          } catch (err) {
            console.error('ajax: expected JSON, got unparseable response:', url, text);
          }
        }
        var handler = statusCode[response.status];
        if (handler) {
          handler(payload, response);
        }
        return { payload: payload, response: response };
      });
    })
    .catch(err => {
      console.error('ajax request failed:', url, err);
      throw err;
    })
    .finally(() => {
      ajax.pending--;
    });
}
ajax.pending = 0;

// Mirrors jQuery.param()'s bracket-notation for array values (key[]=a&key[]=b).
function ajaxSerialize(data) {
  var params = new URLSearchParams();
  Object.keys(data).forEach(key => {
    var value = data[key];
    if (Array.isArray(value)) {
      value.forEach(v => params.append(`${key}[]`, v));
    } else if (value !== undefined && value !== null) {
      params.append(key, value);
    }
  });
  return params.toString();
}
