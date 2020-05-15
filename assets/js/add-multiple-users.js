;(function(window, document, $, $context) {

  const outputMessageOnTable = (message) => {
    const tbody = $('#lorem-user-generator table tbody');
    tbody.html('<tr><td colspan="6">'+ message +'</td></tr>');
  };

  const renderResultHtmlRowForResult = (result, tbody) => {
    const rowAsArray = [
      '<td>'+ result.first_name +'</td>',
      '<td>'+ result.last_name +'</td>',
      '<td>'+ result.email +'</td>',
      '<td><pre>'+ result.username +'</pre></td>',
      '<td><pre>'+ result.password +'</pre></td>',
      '<td></td>',
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

  const getOptionsForm = () => $('main#lorem-user-generator form');

  const onDocumentFinishesLoading = () => {
    getOptionsForm().on('submit', onFormSubmission);
  };

  $(document).ready(onDocumentFinishesLoading);
})(window, window.document, jQuery, LoremUserGenerator);
