$(document).ready(function () {
  var html = '';
  $('.data_per_year').on('mouseenter', function() {
    html = $('.data_per_year a').html();
    $('.data_per_year a').html((Math.round($(this).data('per-year') * 10) / 10));
  });
  $('.data_per_year').on('mouseleave', function() {
    $('.data_per_year a').html(html);
  })

  var html_user = '';
  $('.data_per_year_user').on('mouseenter', function() {
    html_user = $('.data_per_year_user a').html();
    $('.data_per_year_user a').html((Math.round($(this).data('per-year') * 10) / 10));
  });
  $('.data_per_year_user').on('mouseleave', function() {
    $('.data_per_year_user a').html(html_user);
  })
});