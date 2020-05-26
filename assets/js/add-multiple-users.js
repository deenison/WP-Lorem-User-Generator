;(function(window, document, $, $context) {

  $('#form-save-users').on(
    'submit',
    function (event) {
      event.preventDefault();

      console.log('lorem');

      return false;
    }
  );

  const outputMessageOnTable = (message) => {
    const tbody = $('#lorem-user-generator table tbody');
    tbody.html('<tr><td colspan="6">'+ message +'</td></tr>');
  };

  const renderResultHtmlRowForResult = (result, tbody) => {
    const rowAsArray = [
      '<td>'+ result.first_name +'<input type="hidden" name="first_name[]" value="'+ result.first_name +'" /></td>',
      '<td>'+ result.last_name +'<input type="hidden" name="last_name[]" value="'+ result.last_name +'" /></td>',
      '<td>'+ result.email +'<input type="hidden" name="email[]" value="'+ result.email +'" /></td>',
      '<td><pre>'+ result.username +'</pre><input type="hidden" name="username[]" value="'+ result.username +'" /></td>',
      '<td><pre>'+ result.password +'</pre><input type="hidden" name="password[]" value="'+ result.password +'" /></td>',
    ];

    tbody.append('<tr>'+ rowAsArray.join() +'</tr>');
  };

  const handleSuccessfulActionResponse = (results) => {
    const tbody = $('#lorem-user-generator table tbody');
    tbody.html('');

    Array
      .from(results)
      .forEach(result => renderResultHtmlRowForResult(result, tbody));
  };

  const handleErrorActionResponse = errorMessage => outputMessageOnTable(errorMessage);
  const handleFailActionResponse = failMessage => outputMessageOnTable(failMessage);
  const handleUnexpectedResponseStatusActionResponse = unexpectedStatus => outputMessageOnTable('Unexpected response status: `'+ unexpectedStatus +'`');

  const handleActionResponse = (response) => {
    console.log(response);

    switch (response.status) {
      case 'success': return handleSuccessfulActionResponse(response.data);
      case 'error': return handleErrorActionResponse(response.error);
      case 'fail': return handleFailActionResponse(response.error);
      default: return handleUnexpectedResponseStatusActionResponse(response.status);
    }

  };

  const fetchRandomData = (onBeforeSend, onSucceed, onError, onComplete) => {
    $.ajax({
      type: 'GET',
      url: window.ajaxurl,
      data: {
        action: 'lorem_user_generator_fetch_multiple_random_data',
        nonce: $context.nonce,
        qty: $('#lorem-user-generator form input[name="quantity"]').val(),
      },
      beforeSend: onBeforeSend,
      success: onSucceed,
      error: onError,
      complete: onComplete,
    });
  };

  const onFormSubmission = function(event) {
    event.preventDefault();

    fetchRandomData(
      () => $('#lorem-user-generator table tbody').html('<tr><td colspan="6">Loading...</td></tr>'),
      handleActionResponse
    );

    return false;
  };

  const getOptionsForm = () => $('#form-fetch');

  const onDocumentFinishesLoading = () => {
    getOptionsForm().on('submit', onFormSubmission);
    $('#form-save-users').on(
      'submit',
      function (event) {
        event.preventDefault();

        const form = $(event.target);

        const data = $('table tbody tr', form).map(function (userIndex, tr) {
          return {
            'first_name': $('input[name="first_name[]"]', tr).val(),
            'last_name': $('input[name="last_name[]"]', tr).val(),
            'email': $('input[name="email[]"]', tr).val(),
            'username': $('input[name="username[]"]', tr).val(),
            'password': $('input[name="password[]"]', tr).val(),
          };
        });

        $.ajax({
          type: 'POST',
          url: window.ajaxurl,
          data: {
            action: 'lorem_user_generator_save_multiple_random_data',
            nonce: $context.nonce,
            users: Array.from(data),
          },
        });

        return false;
      }
    );
  };

  $(document).ready(onDocumentFinishesLoading);
})(window, window.document, jQuery, LoremUserGenerator);
