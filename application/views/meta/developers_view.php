<div class="main_container no_heading">
  <div class="full_container">
    <div class="container">
      <h1>Developers</h1>
    </div>
    <div class="container">
      <p>Here are the available information for the developers.</p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>api/artist/get</h2>
      <p>Returns listened artists for the given user.</p>
      <h3>@param array $opts.</h3>
      <pre>
        'lower_limit' => Lower date limit in yyyy-mm-dd format
        'upper_limit' => Upper date limit in yyyy-mm-dd format
        'username' => Username
        'artist_name' => Artist name
        'group_by' => Group by argument
        'order_by' => Order by argument
        'limit' => Limit
      <h3>@return string</h3>
      <pre>
        JSON encoded data containing artist information.</pre>
      <h3>Example request</h3>
      <p class="meta"><?=anchor('api/artist/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true', 'http://statster.info/api/artist/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true')?></p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>api/album/get</h2>
      <p>Returns listened albums for the given user.</p>
      <h3>@param array $opts.</h3>
      <pre>
        'lower_limit' => Lower date limit in yyyy-mm-dd format
        'upper_limit' => Upper date limit in yyyy-mm-dd format
        'username' => Username
        'artist_name' => Artist name
        'album_name' => Album name
        'group_by' => Group by argument
        'order_by' => Order by argument
        'limit' => Limit
      <h3>@return string</h3>
      <pre>
        JSON encoded data containing album information.</pre>
      <h3>Example request</h3>
      <p class="meta"><?=anchor('api/album/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true', 'http://statster.info/api/album/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true')?></p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>api/listening/get</h2>
      <p>Returns recently listened albums for the given user.</p>
      <h3>@param array $opts.</h3>
      <pre>
        'username' => Username
        'artist_name' => Artist name
        'album_name' => Album name
        'date' => Listening date in yyyy-mm-dd format
        'limit' => Limit
      <h3>@return string</h3>
      <pre>
        JSON encoded data containing listening information</pre>
      </pre>
      <h3>Example request</h3>
      <p class="meta"><?=anchor('api/listening/get?username=teelmo&album_name=lateralus&date=2008-11-%&limit=10&no_content=true', 'http://statster.info/api/listening/get?username=teelmo&album_name=lateralus&date=2008-11-%&limit=10&no_content=true')?></p>
    </div>
    <div class="container">
      <p class="updated">
        Updated: 5 August 2023
      </p>
    </div>
  </div>