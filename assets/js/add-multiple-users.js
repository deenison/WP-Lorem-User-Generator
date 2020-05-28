;(function(window, document, $, $context) {

  const AJAX_URL = window.ajaxurl;

  function sendHttpRequest(
    httpMethod,
    payload,
    onBeforeSend,
    onSuccess,
    onError,
    onComplete
  ) {
    $.ajax({
      type: httpMethod,
      url: AJAX_URL,
      data: payload,
      beforeSend: onBeforeSend,
      success: onSuccess,
      error: onError,
      complete: onComplete
    });
  }

  function enableButton(buttonElement) {
    buttonElement
      .attr('disabled', null)
      .removeClass('disabled');
  }

  function disableButton(buttonElement) {
    buttonElement
      .attr('disabled', 'disabled')
      .addClass('disabled');
  }

  const $console = {
    selector: 'main#lorem-user-generator #output-console-wrapper',
    display: function () { $(this.selector).show(); },
    hide: function () { $(this.selector).hide(); },
    setContent: function (content) { $('#output-console-content', $(this.selector)).text(content); },
    clear: function () { this.setContent(''); },
  };

  const $usersOutputTable = {
    selector: 'main#lorem-user-generator form#form-save-users table',
    pushUserData: function (user) {
      const tbody = $('tbody', $(this.selector));

      const rowAsArray = [
        '<td>'+ user.first_name +'<input type="hidden" name="first_name[]" value="'+ user.first_name +'" /></td>',
        '<td>'+ user.last_name +'<input type="hidden" name="last_name[]" value="'+ user.last_name +'" /></td>',
        '<td>'+ user.email +'<input type="hidden" name="email[]" value="'+ user.email +'" /></td>',
        '<td><pre>'+ user.username +'</pre><input type="hidden" name="username[]" value="'+ user.username +'" /></td>',
        '<td><pre>'+ user.password +'</pre><input type="hidden" name="password[]" value="'+ user.password +'" /></td>',
      ];

      tbody.append('<tr>'+ rowAsArray.join() +'</tr>');
    },
  };

  const $fetchButton = {
    selector: 'main#lorem-user-generator form#form-fetch button[type="submit"]',
    enable: function () { enableButton($(this.selector)); },
    disable: function () { disableButton($(this.selector)); },
  };

  const $fetchForm = {
    selector: 'main#lorem-user-generator form#form-fetch',
    attachEvents: function () {
      $(this.selector).on('submit', this._onFormSubmit);
    },
    sendHttpRequest: function () {
      const requestPayload = {
        action: 'lorem_user_generator_fetch_multiple_random_data',
        nonce: $context.nonce,
        qty: $('#lorem-user-generator form input[name="quantity"]').val(),
      };

      sendHttpRequest(
        'GET',
        requestPayload,
        this._onBeforeSendHttpRequest,
        this._onSuccessfulHttpRequestResponse,
      );
    },
    _onFormSubmit: function (event) {
      event.preventDefault();
      $fetchForm.sendHttpRequest();
      return false;
    },
    _onBeforeSendHttpRequest: function () {
      $console.setContent('Generating...');
      $console.display();
      $saveButton.disable();
    },
    _onSuccessfulHttpRequestResponse: function (response) {
      switch (response.status) {
        case 'success': return $fetchForm._onFetchSucceed(response.data);
        case 'error':
        case 'fail': return $fetchForm._onFetchError(response.error);
        default: return $fetchForm._onUnexpectedHttpRequestResponseStatus(response.status);
      }
    },
    _onFetchSucceed: function (results) {
      $console.hide();
      $console.clear();

      const tbody = $('#lorem-user-generator table tbody');
      tbody.html('');

      const resultsAsArray = Array.from(results);
      if (resultsAsArray.length > 0) {
        resultsAsArray.forEach(user => $usersOutputTable.pushUserData(user));
        $saveButton.enable();
      }
    },
    _onFetchError: function (errorMessage) {
      $console.setContent(errorMessage);
    },
    _onUnexpectedHttpRequestResponseStatus: (unexpectedStatus) => {
      const errorMessage = 'Unexpected response status: `'+ unexpectedStatus +'`.';
      $console.setContent(errorMessage);
    },
  };

  const $saveButton = {
    selector: 'main button[type="submit"][form="form-save-users"]',
    enable: function () { enableButton($(this.selector)); },
    disable: function () { disableButton($(this.selector)); },
  };

  const $saveForm = {
    selector: '#form-save-users',
    attachEvents: function () {
      $(this.selector).on('submit', this.onFormSubmit);
    },
    onFormSubmit: function (event) {
      event.preventDefault();
      $saveForm.sendHttpRequest();
      return false;
    },
    sendHttpRequest: function () {
      sendHttpRequest(
        'POST',
        {
          action: 'lorem_user_generator_save_multiple_random_data',
          nonce: $context.nonce,
          users: this.getFormData(),
        },
        () => {
          $fetchButton.disable();
          $saveButton.disable();

          $console.display();
          $console.setContent('Saving...');
        },
        (response) => {
          $console.display();

          const enableActionButtons = () => {
            $fetchButton.enable();
            $saveButton.enable();
          };

          if (response.status !== 'success') {
            enableActionButtons();
            const errorMessage = 'Unexpected response status: `'+ response.status +'`.';
            return $console.setContent(errorMessage);
          }

          if (!response.redirect_url) {
            enableActionButtons();
            const errorMessage = 'Redirect url is missing.';
            return $console.setContent(errorMessage);
          }

          $console.setContent('Save action succeeded!');
          return window.location.replace(response.redirect_url);
        },
        (errorMessage) => {
          $fetchButton.enable();
          $saveButton.enable();

          $console.display();
          if (errorMessage) {
            return $console.setContent(errorMessage);
          }

          return $console.setContent('Something unexpected happen. Please, try again later.');
        },
      );
    },
    getFormData: function () {
      const formData = $('table tbody tr', $(this.selector)).map(
        function (trIndex, tr) {
          return {
            'first_name': $('input[name="first_name[]"]', tr).val(),
            'last_name': $('input[name="last_name[]"]', tr).val(),
            'email': $('input[name="email[]"]', tr).val(),
            'username': $('input[name="username[]"]', tr).val(),
            'password': $('input[name="password[]"]', tr).val(),
          };
        }
      );
      return Array.from(formData);
    }
  };

  const onDocumentFinishesLoading = () => {
    $fetchForm.attachEvents();
    $saveForm.attachEvents();
  };

  $(document).ready(onDocumentFinishesLoading);
})(window, window.document, jQuery, LoremUserGenerator);
