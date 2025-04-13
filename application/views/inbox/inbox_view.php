<div class="main_container">
  <div class="left_container">
    <div class="container">
      <h2>Compose</h2>
      <?=form_open('', array('class' => '', 'id' => 'composeForm'), array('composeType' => 'form'))?>
        <div>
          <input type="text" autocomplete="off" tabindex="1" id="composeRecipent" placeholder="Enter recipent(s)" class="composeText" name="composeRecipent" />
        </div>
        <div>
          <textarea tabindex="2"></textarea>
        </div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Your inbox</h2>
    </div>
    <div class="container"><hr /></div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2></h2>
    </div>
  </div>