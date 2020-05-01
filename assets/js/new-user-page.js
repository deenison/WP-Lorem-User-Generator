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
          clearInterval(needleElementCheckerInterval);
          return callback(null);
        }

        needleElement = $(needleSelector);
        if (needleElement.length > 0) {
          clearInterval(needleElementCheckerInterval);
          return callback(needleElement);
        }
      },
      CHECK_INTERVAL_IN_MILLISECONDS
    );
  }

  function handleRequestResponse(response) {
    const fetchStatus = response.status || '';

    if (fetchStatus === 'success') {
      return handleSuccessfulResponse(response.data);
    }

    return handleFailedResponse(response.error);
  }

  const outputWrapper = {
    setContent: (content) => {
      $('#lorem-user-generator output').text(content);
    },
    clearContent: () => {
      $('#lorem-user-generator output').text('');
    },
  };

  function handleSuccessfulResponse(user) {
    outputWrapper.clearContent();

    $('#createuser input[name="user_login"]').val(user.username);
    $('#createuser input[name="email"]').val(user.email);
    $('#createuser input[name="first_name"]').val(user.first_name);
    $('#createuser input[name="last_name"]').val(user.last_name);
    $('#createuser input[name="pass1"]')
      .val(user.password)
      .attr('data-pw', user.password)
      .attr('disabled', null);

    $('#createuser .button.wp-generate-pw.hide-if-no-js').css('display', 'none');
    $('#createuser .wp-pwd.hide-if-js').css('display', 'block');
  }

  function clearForm() {
    $('#createuser input[name="user_login"]').val('');
    $('#createuser input[name="email"]').val('');
    $('#createuser input[name="first_name"]').val('');
    $('#createuser input[name="last_name"]').val('');
    $('#createuser input[name="pass1"]')
      .val('')
      .attr('data-pw', '')
      .attr('disabled', null);
  }

  function handleFailedResponse(errorMessage) {
    outputWrapper.setContent(errorMessage);
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
        outputWrapper.setContent('Trying to reach remote data...');
      },
      success: handleRequestResponse,
      error: function(request, textStatus, err) {
      },
      complete: function() {
      }
    });
  }

  function createFetchButton() {
    const wrapper = $(
      '<section id="lorem-user-generator" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 15px 0 15px 0;">' +
        '<h3>Lorem User Generator</h3>' +
        '<button type="button" data-action="fetch">Fill form with random data</button>' +
        '<button type="button" data-action="clear-form">Clear form</button>' +
        '<output style="display: block;"></output>' +
      '</section>');

    $('button[data-action="fetch"]', wrapper).on('click', sendFetchRandomUserRequest);
    $('button[data-action="clear-form"]', wrapper).on('click', clearForm);

    return wrapper;
  }

  getElementWhenAvailable(
    '#createuser',
      form => {
      const fetchBtn = createFetchButton();
      $(form).before(fetchBtn);
    }
  );
})(window, window.document, jQuery, LoremUserGenerator);
