<div id="leftCont">
  <div class="container">
    <h1>Login</h1>
      <?=form_open('', array('class' => '', 'id' => 'loginForm'), array('addListeningType' => 'form'))?>
      <div>
        <input type="text" class="" autocomplete="off" tabindex="2" id="loginUsername" placeholder="Enter username" name="registerUsername" />
      </div>
      <div>
        <input type="password" class="" autocomplete="off" tabindex="2" id="loginPassword" placeholder="Enter password" name="registerEmail" />
      </div>
      <div>
        <p>
          <input type="submit" name="loginSubmit" tabindex="2" id="loginSubmit" value="Login" />
        </p>
      </div>
    </form>
    <p>
      After clicking login please click <?=anchor('/', 'here')?>.
    </p>
  </div>
  <div class="container"><hr /></div>
</div>

<div id="rightCont">
  <div class="container">
    
  </div>
</div>