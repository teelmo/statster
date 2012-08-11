<div id="leftCont">
  <div class="container">
    <h1>Edit profile</h1>
    <?=form_open('', array('class' => '', 'id' => 'editProfileForm'), array('editProfile' => 'form'))?>
      <fieldset><legend>Statstering information</legend>
        
      </fieldset>

      <fieldset><legend>Personal information</legend>
        <div>
          <label for="profileRealName">Real name</label>
          <br />
          <input placeholder="Give your name" id="profileRealName" type="text" />
        </div>
        <div>
          <label for="profileRealName">About you</label>
          <br />
          <textarea placeholder="Describe yourself" cols="55" rows="7" autocomplete="off"></textarea>
        </div>
      </fieldset>
    </form>
  </div>
  <div class="container"><hr /></div>
</div>

<div id="rightCont">
  <div class="container">
    <h1></h1>
  </div>
</div>