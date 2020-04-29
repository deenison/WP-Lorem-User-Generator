;(function(window, document, $, $context) {
  const FETCH_RANDOM_USER_ENDPOINT = window.ajaxurl;

  function getElementWhenAvailable(needleSelector, callback) {
    if (typeof needleSelector !== 'string' || needleSelector.length === 0) {
      throw new TypeError('`needleSelector` must be a non-empty string');
    }

    if (typeof callback !== 'function') {
      throw new TypeError('`callback` must be callable');
    }

    const MAX_CHECK_COUNT = 100;
    const CHECK_INTERVAL_IN_MILLISECONDS = 50;

    let checkCounter = 0;
    let needleElement = null;

    let needleElementCheckerInterval = setInterval(
      function () {
        checkCounter += 1;

        if (checkCounter >= MAX_CHECK_COUNT) {
          clearInterval(formInstanceCheckerInterval);
          return callback(null);
        }

        needleElement = document.getElementById(needleSelector);
        if (needleElement) {
          clearInterval(needleElementCheckerInterval);
          return callback(needleElement);
        }
      },
      CHECK_INTERVAL_IN_MILLISECONDS
    );
  }

  function sendFetchRandomUserRequest() {
    $.ajax({
      type: 'GET',
      url: FETCH_RANDOM_USER_ENDPOINT,
      data: {
        action: 'lorem_user_generator_fetch_random_data',
        nonce: $context.nonces.fetch_random_data,
      },
      beforeSend: function() {
      },
      success: function(response) {
        $('#createuser input[name="user_login"]').val(response.data.username);
        $('#createuser input[name="email"]').val(response.data.email);
        $('#createuser input[name="first_name"]').val(response.data.first_name);
        $('#createuser input[name="last_name"]').val(response.data.last_name);
        $('#createuser input[name="pass1"]')
          .val(response.data.password)
          .attr('data-pw', response.data.password)
          .attr('disabled', null);

        $('#createuser .button.wp-generate-pw.hide-if-no-js').css('display', 'none');
        $('#createuser .wp-pwd.hide-if-js').css('display', 'block');
      },
      error: function(request, textStatus, err) {
      },
      complete: function() {
      }
    });
  }

  function createFetchButton() {
    const btn = document.createElement('button');
    btn.innerText = 'Lorem User Generator';
    btn.addEventListener('click', sendFetchRandomUserRequest);
    return btn;
  }

  function insertFetchButtonIntoElement(needle, haystack) {
    haystack.insertAdjacentElement('beforeBegin', needle);
  }

  getElementWhenAvailable(
    'createuser',
      form => {
      const fetchBtn = createFetchButton();
      insertFetchButtonIntoElement(fetchBtn, form);
    }
  );
})(window, window.document, jQuery, LoremUserGenerator);
