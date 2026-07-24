// media/js/ajax.js
// fetch()-based replacement for $.ajax(), shaped to match its call signature
// (data/type/dataType/statusCode/success/error/complete) so call sites need
// minimal changes. Static file, no PHP interpolation - loaded as a real
// <script defer> in header.php rather than spliced through footer.php like
// the page-specific JS files.
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
        var statusHandler = statusCode[response.status];
        if (statusHandler) {
          statusHandler(payload, response);
        }
        if (response.ok) {
          if (opts.success) {
            opts.success(payload, response);
          }
        } else if (opts.error) {
          opts.error(response, payload);
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
      if (opts.complete) {
        opts.complete();
      }
    });
}
ajax.pending = 0;

// Mirrors jQuery.param()'s bracket-notation for arrays (key[]=a&key[]=b) and
// nested plain objects (key[sub]=a&key[other]=b), since PHP's $_GET/$_POST
// parse that same bracket syntax into nested arrays on the other end (e.g.
// hide: {artist: true} -> hide[artist]=true -> $data['hide']['artist']).
function ajaxSerialize(data) {
  var params = new URLSearchParams();
  ajaxAppend(params, data, null);
  return params.toString();
}

function ajaxAppend(params, value, prefix) {
  if (Array.isArray(value)) {
    // Explicit indices, not bare key[] - PHP splits key[][a]=1&key[][b]=2 into
    // two SEPARATE elements ({a:1} and {b:2}) instead of one ({a:1,b:2}), since
    // [] means "next new element" independently at each occurrence.
    value.forEach((v, i) => ajaxAppend(params, v, `${prefix}[${i}]`));
  } else if (value !== null && typeof value === 'object') {
    Object.keys(value).forEach(key => {
      ajaxAppend(params, value[key], prefix === null ? key : `${prefix}[${key}]`);
    });
  } else if (value !== undefined && value !== null) {
    params.append(prefix, value);
  }
}
