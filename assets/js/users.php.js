;(function(window, document, $, $context) {

  const $addMultipleNewUsersButton = {
    render: function () {
      const button = this._create();
      $('#wpbody-content a.page-title-action[href$="/wp-admin/user-new.php"]').after(button);
    },
    _create: function () {
      const button = $('<a href="http://localhost:8080/wp-admin/user-new.php" class="page-title-action">Add Multiple New</a>');
      button.on('click', this._onClick);
      return button;
    },
    _onClick: function (event) {
      event.preventDefault();
      window.location.replace($context.add_multiple_new_url);
      return false;
    },
  };

  const onDocumentFinishesLoading = () => {
    $addMultipleNewUsersButton.render();
  };

  $(document).ready(onDocumentFinishesLoading);
})(
  window || {},
  window.document || {},
  jQuery || {},
  LoremUserGenerator || {}
);
