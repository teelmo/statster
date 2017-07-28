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
            <label><div><input type="radio" name="gender" value="M" <?php if ($gender === 'M') { echo 'checked="checked"'; }?> /> Male</div></label>
            <label><div><input type="radio" name="gender" value="F" <?php if ($gender === 'N') { echo 'checked="checked"'; }?> /> Female</div></label>
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
        <fieldset>
          <legend>Listening settings</legend>
          <div class="input_container">
            <?php
            foreach ($formats as $format_key => $format) {
              if (isset($format->format_types)) {
                ?>
                <label><div class="middle checkbox_container"><input type="checkbox" name="listening_formats[]" value="<?=$format->format_id?>" <?php if (in_array($format->format_id, $formats)) { echo 'checked="checked"'; }?> /> <img src="/media/img/format_img/format_icons/<?=$format->format_img?>" alt="" class="middle"/> <?=ucfirst($format->format_name)?></div></label>
                <?php
                foreach ($format->format_types as $format_type_key => $format_type) {
                  ?>
                  <label><div class="middle checkbox_container">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="listening_formats[]" value="<?=$format->format_id?>:<?=$format_type->format_type_id?>" <?php if (in_array($format->format_id . ':'. $format_type->format_type_id, $formats)) { echo 'checked="checked"'; }?> /> <img src="/media/img/format_img/format_icons/<?=$format_type->format_type_img?>" alt="" class="middle"/> <?=ucfirst($format_type->format_type_name)?></div></label>
                  <?php
                }
              }
              else {
                ?>
                <label><div class="middle checkbox_container"><input type="checkbox" name="listening_formats[]" value="<?=$format->format_id?>" <?php if (in_array($format->format_id, $formats)) { echo 'checked="checked"'; }?> /> <img src="/media/img/format_img/format_icons/<?=$format->format_img?>" alt="" class="middle"/> <?=ucfirst($format->format_name)?></div></label>
                <?php
              }
            }
            ?>
          </div>
        </fieldset>
        <fieldset>
          <legend>Privacy settings</legend>
          <div class="input_container">
            <?php
            foreach (unserialize($privacy_settings) as $key => $value) {
              ?>
              <input type="hidden" name="privacy_settings[<?=$key?>]" value="0" />
              <label><div class="checkbox_container"><input type="checkbox" name="privacy_settings[<?=$key?>]" value="1" <?php if ($value === '1') { echo 'checked="checked"'; }?> /> <?=ucfirst($key)?></div></label>
              <?php
            }
            ?>
          </div>
        </fieldset>
        <fieldset>
          <legend>Social media settings</legend>
          <div class="input_container">
            <?php
            foreach (unserialize($social_media_settings) as $key => $value) {
              ?>
              <input type="hidden" name="social_media_settings[<?=$key?>]" value="0" />
              <label><div class="checkbox_container"><input type="checkbox" name="social_media_settings[<?=$key?>]" value="1" <?php if ($value === '1') { echo 'checked="checked"'; }?> /> <?=ucfirst($key)?></div></label>
              <?php
            }
            ?>
          </div>
        </fieldset>
        <fieldset>
          <legend>Email annotations</legend>
          <div class="input_container">
            <?php
            foreach (unserialize($email_annotations) as $key => $value) {
              ?>
              <input type="hidden" name="email_annotations[<?=$key?>]" value="0" />
              <label><div class="checkbox_container"><input type="checkbox" name="email_annotations[<?=$key?>]" value="1" <?php if ($value === '1') { echo 'checked="checked"'; }?> /> <?=ucfirst($key)?></div></label>
              <?php
            }
            ?>
          </div>
        </fieldset>
        <fieldset>
          <legend>Bulletin settings</legend>
          <div class="input_container">
            <?php
            foreach (unserialize($bulletin_settings) as $key => $value) {
              ?>
              <input type="hidden" name="bulletin_settings[<?=$key?>]" value="0" />
              <label><div class="checkbox_container"><input type="checkbox" name="bulletin_settings[<?=$key?>]" value="1" <?php if ($value === '1') { echo 'checked="checked"'; }?> /> <?=ucfirst($key)?></div></label>
              <?php
            }
            ?>
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