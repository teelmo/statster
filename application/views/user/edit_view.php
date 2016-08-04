<div id="mainCont">
  <div id="leftCont">
    <div class="container">
      <h1>Edit profile</h1>
      <?=form_open('', array('class' => '', 'id' => 'editProfileForm'), array('editProfile' => 'form'))?>
        <fieldset>
          <legend>Personal information</legend>
          <div class="input_container">
            <label>
              <div class="label">Username</div>
              <div><input type="text" value="<?=$username?>" disabled="disabled" name="username" autocomplete="off" /></div>
            </label>
          </div>
          <div class="input_container">
            <label>
              <div class="label">Real name</div>
              <div><input type="text" value="<?=$real_name?>" name="real_name" autocomplete="off" /></div>
            </label>
          </div>
          <div class="input_container">
            <div class="label">Gender</div>
            <label><div><input type="radio" name="gender" value="M" <?php if ($gender === 'M') { echo 'checked="checked"'; }?> />Male</div></label>
            <label><div><input type="radio" name="gender" value="F" <?php if ($gender === 'N') { echo 'checked="checked"'; }?>/>Female</div></label>
          </div>
          <div class="input_container">
            <label>
              <div class="label">About</div>
              <div><textarea name="about" class="text"><?=$about?></textarea></div>
            </label>
          </div>
        </fieldset>
        <fieldset>
          <legend>Other information</legend>
          <div class="input_container">
            <label>
              <div class="label">Email</div>
              <div><input type="text" value="<?=$email?>" name="email" autocomplete="off" /></div>
            </label>
          </div>
          <div class="input_container">
            <label>
              <div class="label">Homepage</div>
              <div><input type="text" value="<?=$homepage?>" name="homepage" autocomplete="off" /></div>
            </label>
          </div>
          <div class="input_container">
            <label>
              <div class="label">Lastfm</div>
              <div><input type="text" value="<?=$lastfm_name?>" name="lastfm_name" autocomplete="off" /></div>
            </label>
          </div>
        </fieldset>
        <div class="submit_container">
          <input type="submit" name="submit" value="Save profile" />
        </div>
      </form>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1></h1>
    </div>
  </div>