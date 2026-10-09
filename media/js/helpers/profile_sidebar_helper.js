Object.assign(view, {
  getShouts: () =>
    new Promise(resolve => {
      ajax({
        data: {
          username: '<?=$username?>'
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            var shoutTotal = document.querySelector('#shoutTotal');
            if (data[0].count === 1) {
              shoutTotal.innerHTML = `<span class="number">${data[0].count}</span> shout`;
            } else {
              shoutTotal.innerHTML = `<span class="number">${data[0].count}</span> shouts`;
            }
            shoutTotal.style.display = '';
            ajax({
              data: {
                hide: {
                  user: true
                },
                json_data: data,
                size: 64,
                type: 'user'
              },
              success: data => {
                document.querySelector('#userShoutLoader').classList.add('hidden');
                document.querySelector('#userShout').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/shoutTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#userShoutLoader').classList.add('hidden');
            document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/user'
      }).catch(() => resolve());
    }),
  getAlbumShouts: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 5,
          username: '<?=$username?>'
        },
        dataType: 'json',
        success: () => {},
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                hide: {
                  delete: true,
                  user: true
                },
                json_data: data,
                size: 32
              },
              success: data => {
                document.querySelector('#albumShout').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/shoutTable'
            });
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/album'
      }).catch(() => resolve());
    }),
  getArtistShouts: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 5,
          username: '<?=$username?>'
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                hide: {
                  delete: true,
                  user: true
                },
                json_data: data,
                size: 32
              },
              success: data => {
                document.querySelector('#artistShout').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/shoutTable'
            });
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/artist'
      }).catch(() => resolve());
    }),
  recentlyFaned: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 5,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                hide: {
                  rank: true,
                  user: true
                },
                json_data: data
              },
              success: data => {
                document.querySelector('#recentlyFaned').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/likeTable'
            });
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/fan/get'
      }).catch(() => resolve());
    }),
  recentlyLoved: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 5,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                hide: {
                  rank: true,
                  user: true
                },
                json_data: data
              },
              success: data => {
                document.querySelector('#recentlyLoved').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/likeTable'
            });
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/love/get'
      }).catch(() => resolve());
    }),
  getTopFormats: interval =>
    new Promise(resolve => {
      var lower_limit;
      if (interval === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const today = new Date();
        today.setDate(new Date().getDate() - parseInt(interval, 10));
        lower_limit = today.toISOString().split('T')[0];
      }
      ajax({
        data: {
          limit: 5,
          lower_limit: lower_limit,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                hide: {
                  format_icon: true
                },
                json_data: data
              },
              success: data => {
                document.querySelectorAll('#topFormatLoader, #topFormatLoader2').forEach(el => {
                  el.classList.add('hidden');
                });
                document.querySelector('#topFormat').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelectorAll('#topFormatLoader, #topFormatLoader2').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#topFormat').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/format/get'
      }).catch(() => resolve());
    }),
  getTopGenres: interval =>
    new Promise(resolve => {
      var lower_limit;
      if (interval === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const today = new Date();
        today.setDate(new Date().getDate() - parseInt(interval, 10));
        lower_limit = today.toISOString().split('T')[0];
      }
      ajax({
        data: {
          limit: 5,
          lower_limit: lower_limit,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                json_data: data
              },
              success: data => {
                document.querySelectorAll('#topGenreLoader, #topGenreLoader2').forEach(el => {
                  el.classList.add('hidden');
                });
                document.querySelector('#topGenre').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelectorAll('#topGenreLoader, #topGenreLoader2').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#topGenre').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/genre/get'
      }).catch(() => resolve());
    }),
  getTopKeywords: interval =>
    new Promise(resolve => {
      var lower_limit;
      if (interval === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const today = new Date();
        today.setDate(new Date().getDate() - parseInt(interval, 10));
        lower_limit = today.toISOString().split('T')[0];
      }
      ajax({
        data: {
          limit: 5,
          lower_limit: lower_limit,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                json_data: data
              },
              success: data => {
                document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2').forEach(el => {
                  el.classList.add('hidden');
                });
                document.querySelector('#topKeyword').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#topKeyword').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/keyword/get'
      }).catch(() => resolve());
    }),
  getTopNationalities: interval =>
    new Promise(resolve => {
      var lower_limit;
      if (interval === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const today = new Date();
        today.setDate(new Date().getDate() - parseInt(interval, 10));
        lower_limit = today.toISOString().split('T')[0];
      }
      ajax({
        data: {
          limit: 5,
          lower_limit: lower_limit,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                json_data: data
              },
              success: data => {
                document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2').forEach(el => {
                  el.classList.add('hidden');
                });
                document.querySelector('#topNationality').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#topNationality').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/nationality/get/listenings'
      }).catch(() => resolve());
    }),
  getTopYears: interval =>
    new Promise(resolve => {
      var lower_limit;
      if (interval === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const today = new Date();
        today.setDate(new Date().getDate() - parseInt(interval, 10));
        lower_limit = today.toISOString().split('T')[0];
      }
      ajax({
        data: {
          limit: 5,
          lower_limit: lower_limit,
          username: `<?=(!empty($username)) ? $username: ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                json_data: data
              },
              success: data => {
                document.querySelectorAll('#topYearLoader, #topYearLoader2').forEach(el => {
                  el.classList.add('hidden');
                });
                document.querySelector('#topYear').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelectorAll('#topYearLoader, #topYearLoader2').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#topYear').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/year/get'
      }).catch(() => resolve());
    })
});
