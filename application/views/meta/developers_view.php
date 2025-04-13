<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div class="main_container">
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
      <strong>@param array $opts.</strong>
      <pre>
        'lower_limit' => Lower date limit in yyyy-mm-dd format
        'upper_limit' => Upper date limit in yyyy-mm-dd format
        'username' => Username
        'artist_name' => Artist name
        'group_by' => Group by argument
        'order_by' => Order by argument
        'limit' => Limit
      </pre>
      <strong>@return string</strong>
      <pre>
        JSON encoded data containing artist information.
      </pre>
      <strong>Example request</strong>
      <p class="meta"><?=anchor('api/artist/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true', 'http://statster.info/api/artist/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true')?></p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>api/album/get</h2>
      <p>Returns listened albums for the given user.</p>
      <strong>@param array $opts.</strong>
      <pre>
        'lower_limit' => Lower date limit in yyyy-mm-dd format
        'upper_limit' => Upper date limit in yyyy-mm-dd format
        'username' => Username
        'artist_name' => Artist name
        'album_name' => Album name
        'group_by' => Group by argument
        'order_by' => Order by argument
        'limit' => Limit
      </pre>
      <strong>@return string</strong>
      <pre>
        JSON encoded data containing album information.
      </pre>
      <strong>Example request</strong>
      <p class="meta"><?=anchor('api/album/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true', 'http://statster.info/api/album/get?username=teelmo&limit=10&lower_limit=1970-00-00&no_content=true')?></p>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>api/listening/get</h2>
      <p>Returns recently listened albums for the given user.</p>
      <strong>@param array $opts.</strong>
      <pre>
        'username' => Username
        'artist_name' => Artist name
        'album_name' => Album name
        'date' => Listening date in yyyy-mm-dd format
        'limit' => Limit
      </pre>
      <strong>@return string</strong>
      <pre>
        JSON encoded data containing listening information.
      </pre>
      </pre>
      <strong>Example request</strong>
      <p class="meta"><?=anchor('api/listening/get?username=teelmo&artist_name=Tool&album_name=Lateralus&date=2008-11-%&limit=10&no_content=true', 'http://statster.info/api/listening/get?username=teelmo&artist_name=Tool&album_name=Lateralus&date=2008-11-%&limit=10&no_content=true')?></p>
    </div>
    <div class="container">
      <p class="updated">
        Updated: 13 April 2025
      </p>
    </div>
  </div>