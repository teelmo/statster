Object.assign(view, {
  initLoginEvents: () => {
    document.querySelector('#loginUsername').focus();
    document.querySelector('#loginSubmit').addEventListener('click', event => {
      event.preventDefault();
      event.stopPropagation();
      ajax({
        data: {
          password: document.querySelector('#loginPassword').value,
          submitType: document.querySelector('input[name="submitType"]').value,
          username: document.querySelector('#loginUsername').value
        },
        success: data => {
          if (data.trim() === '1') {
            window.location.href = encodeURI('<?=addslashes($redirect)?>').replace(/%20/g, '+');
          } else {
            alert('Wrong username or password. Please try again.');
          }
        },
        type: 'POST',
        url: '/api/login'
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initLoginEvents();
