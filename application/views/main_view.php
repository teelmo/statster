<div id="leftCont">
  <div class="container">
    <h1>What’s ya listening?</h1>
    <?=form_open('', array('class' => 'text', 'id' => 'addListeningForm'))?>
      <input type="text" id="addListening" placeholder="start typing.." /> 
      <!--<input type="submit" />-->
    </form>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Recently listened</h1>
    <table id="recentlyListened" class="chartTable">
      <!-- Content is loaded with AJAX --> 
    </table>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Top albums</h1>
    <ul id="topAlbum" class="chartList">
      <!-- Content is loaded with AJAX --> 
    </ul>
  </div>
  <div class="container"><hr /></div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
  </div>
  <div class="container">
    <ul>
      <li>Most Listened Album Last Month: Tool - Lateralus</li>
      <li>Most Listened Artist Last Month: Penniless</li>
      <li>Listenings in March: 0 <span>(0 in total)</span></li>
      <li>Listenings in 2012: 0 <span>(0 in total)</span></li>
      <li>Your listening count: 13817 <span>(32293 in total)</span></li>
    </ul>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Statster recommends</h1>
    <h2>Recommented albums</h2>
    <ul id="recommentedAlbums">
      <!-- Content is loaded with AJAX -->
    </ul>
    <h2>Recently released</h2>
    <ul id="recentlyReleased">  
      <!-- Content is loaded with AJAX -->
    </ul>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Latest blog posts</h1>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Browse Statster</h1>
    <ul>
      <li><?=anchor(array('music'), 'Browse music', array('title' => 'Browse music'))?></li>
      <li><?=anchor(array('user'), 'Browse users', array('title' => 'Browse users'))?></li>
      <li><?=anchor(array('metadata'), 'Browse metadata', array('title' => 'Browse metadata'))?></li>
    </ul>
  </div>
</div>