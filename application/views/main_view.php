<div id="leftCont">
  <div class="container">
    <h1>What’s ya listening?</h1>
    <?=form_open('', array('class' => 'text', 'id' => 'addListeningForm'))?>
      <input type="text" id="addListening" placeholder="start typing.." />
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
    <!--<h1>Statistics</h1>-->
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
</div>