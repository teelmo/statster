<?php $this->load->view('templates/heading_main'); ?>
    <div class="container">
      <h2>Login</h2>
        <?=form_open('', array('class' => '', 'id' => 'loginForm'), array('addListeningType' => 'form'))?>
        <div>
          <input type="text" class="" tabindex="2" id="loginUsername" placeholder="Enter username" name="registerUsername" />
        </div>
        <div>
          <input type="password" class="" tabindex="2" id="loginPassword" placeholder="Enter password" name="registerEmail" />
        </div>
        <br />
        <div>
          <p><input type="submit" name="loginSubmit" tabindex="2" id="loginSubmit" value="Login" /></p>
        </div>
      </form>
    </div>
    <div class="container"><hr /></div>
  </div>
  <div class="right_container">
    <div class="container"></div>
  </div>