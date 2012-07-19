<div id="leftCont">
  <div class="container">
    <div class="floatRight">
      <?=anchor(array('login'), 'Login', array('title' => 'Login'))?>
    </div>
    <h1>Statster&nbsp; &middot; &nbsp; &middot; &nbsp; &middot; &nbsp;welcome!</h1>
    <p>
      Want to be reconciled with the music. Do it Statster like and <a href="javascript:" onclick="$('#registerForm').toggle(); return false;">register now!</a> 
    </p>
    <?=form_open('', array('class' => 'hidden', 'id' => 'registerForm'), array('addListeningType' => 'form'))?>
      <div>
        <input type="text" autocomplete="off" tabindex="1" id="registerUsername" placeholder="Desired username" name="registerUsername" />
      </div>
      <div>
        <input type="text" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter your email" name="registerEmail" />
      </div>
      <div>
        <input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter a password" name="registerEmail" />
      </div>
      <div>
        <input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Re-enter your password" name="registerEmail" />
      </div>
      <div>
        <p>
          <input type="submit" name="registerSubmit" tabindex="2" id="registerSubmit" value="Register!" />
        </p>
      </div>
    </form>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <iframe width="570" height="315" src="http://www.youtube.com/embed/f0pdwd0miqs" frameborder="0" allowfullscreen></iframe>
  </div>
  <div class="container">
    
  </div>
  <div class="container"><hr /></div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Recently listened albums</h1>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>All time top artists</h1>
  </div>

</div>


