<div id="leftCont">
  <div class="container">
    <h1>Developers</h1>
  </div>
  <div class="container">
    <p>
      Here are the available information for the developers.
    </p>
  </div>
  <div class="container">
    <h2>API</h2>
    <p>
      HTTP API responces are in JSON-format.
    </p>
    <p>
      <strong>api/addListening</strong>
    </p>
    <p>
      <strong>api/listArtist</strong>
    </p>
    <code>
      Returns listened artists for the given user.</br />
      <br  />
      @param array $opts.</br />
      &nbsp;&nbsp;'username' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Username</br />
      &nbsp;&nbsp;'order_by' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Order by argument</br />
      &nbsp;&nbsp;'limit' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Limit</br />
      &nbsp;&nbsp;'human_readable' => Output</br />
      </br />
      @return string JSON encoded data containing artist information.</br />
    </code>
    <p class="small">
      <?=anchor('api/listAlbum?username=teelmo&order_by=artist_name+asc&limit=10&human_readable=true', 'http://statster.info/api/listAlbum?username=teelmo&order_by=artist_name+asc&limit=10&human_readable=false')?>
    </p>
    <p>
      <strong>api/listAlbum</strong>
    </p>
    <code>
      Returns listened albums for the given user.</br />
      <br  />
      @param array $opts.</br />
      &nbsp;&nbsp;'username' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Username</br />
      &nbsp;&nbsp;'order_by' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Order by argument</br />
      &nbsp;&nbsp;'limit' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Limit</br />
      &nbsp;&nbsp;'human_readable' => Output</br />
      </br />
      @return string JSON encoded data containing album information.</br />
    </code>
    <p class="small">
      <?=anchor('api/listAlbum?username=teelmo&order_by=album_name+asc&limit=10&human_readable=true', 'http://statster.info/api/listAlbum?username=teelmo&order_by=album_name+asc&limit=10&human_readable=false')?>
    </p>
    <p>
      <strong>api/recentlyListened</strong>
    </p>
    <code>
      Returns recently listened albums for the given user.</br />
      </br />
      @param array $opts.
      &nbsp;&nbsp;'username' &nbsp;&nbsp;&nbsp;&nbsp; => Username</br />
      &nbsp;&nbsp;'artist' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Artist name</br />
      &nbsp;&nbsp;'album' &nbsp;&nbsp;&nbsp;&nbsp; => Album name</br />
      &nbsp;&nbsp;'date' &nbsp;&nbsp;&nbsp;&nbsp; => Listening date in yyyy/mm/dd format</br />
      &nbsp;&nbsp;'limit' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; => Limit</br />
      &nbsp;&nbsp;'human_readable' => Output format</br />
      </br />
      @return string JSON encoded data containing album information.
    </code>
    <p class="small">
      <?=anchor('api/recentlyListened?username=teelmo&artist=tool&album=lateralus&date=2008-11-%&limit=10&human_readable=true', 'http://statster.info/api/recentlyListened?username=teelmo&artist=tool&album=lateralus&date=2008-11-%&limit=10&human_readable=true')?>
    </p>
    <p>
      <strong>api/recommetedNewAlbum</strong>
      <br />
    </p>
    <p>
      <strong>api/recommetedTopAlbum</strong>
      <br />
    </p>
    <p>
      <strong>api/topAlbum</strong>
      <br />
    </p>
    <p>
      <strong>api/topArtist</strong>
      <br />
    </p>
    <p>
      <strong>api/topGenre</strong>
      <br />
    </p>
  </div>
      
  <div class="container">
    <h2>Plugins</h2>
  </div>
  <div class="container">
    <p class="updated">
      Updated: 2. of July 2012
    </p>
  </div>
  <div class="container"><hr /></div>
</div>

<div id="rightCont">
  <div class="container">
    
  </div>
</div>