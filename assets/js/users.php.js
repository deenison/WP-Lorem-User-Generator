;(function(window, document, $, $context) {

  const ANCHOR_HREF = $context.anchor.href || '#';
  const ANCHOR_LABEL = $context.anchor.label || 'Add Multiple New';

  const $addMultipleNewUsersButton = {
    render: function () {
      const button = this._create();
      $('#wpbody-content a.page-title-action[href$="/wp-admin/user-new.php"]').after(button);
    },
    _create: function () {
      return $('<a href="'+ ANCHOR_HREF +'" class="page-title-action">'+ ANCHOR_LABEL +'</a>');
    },
  };

  $(document).ready(() => $addMultipleNewUsersButton.render());
})(
  window || {},
  window.document || {},
  jQuery || {},
  LoremUserGenerator || {}
);
