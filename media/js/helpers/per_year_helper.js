var html = '';
document.querySelectorAll('.data_per_year').forEach(el => {
  el.addEventListener('mouseenter', function () {
    html = document.querySelector('.data_per_year a').innerHTML;
    var value = Math.round(this.dataset.perYear * 10) / 10;
    document.querySelectorAll('.data_per_year a').forEach(a => {
      a.innerHTML = value;
    });
  });
  el.addEventListener('mouseleave', () => {
    document.querySelectorAll('.data_per_year a').forEach(a => {
      a.innerHTML = html;
    });
  });
});

var html_user = '';
document.querySelectorAll('.data_per_year_user').forEach(el => {
  el.addEventListener('mouseenter', function () {
    html_user = document.querySelector('.data_per_year_user a').innerHTML;
    var value = Math.round(this.dataset.perYear * 10) / 10;
    document.querySelectorAll('.data_per_year_user a').forEach(a => {
      a.innerHTML = value;
    });
  });
  el.addEventListener('mouseleave', () => {
    document.querySelectorAll('.data_per_year_user a').forEach(a => {
      a.innerHTML = html_user;
    });
  });
});
