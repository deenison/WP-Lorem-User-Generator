(function(window, document, $, $i18n, undefined) {
  if (!$) {
    return console.error('jQuery not found.');
  }

  if (!$i18n) {
    console.warn('Language object not found. Using English instead.');

    $i18n = {
      LB_GENERATING               : 'Generating...',
      MSG_RESULTS_WILL_APPEAR_HERE: 'Results will appear here...',
      LB_GENERATE                 : 'Generate',
      LB_RESULTS                  : 'Results (%d)',
      LB_ADDING                   : 'Adding...',
      MSG_UNDOCUMMENTED_ERROR     : 'Undocummented error.',
      LB_EDIT                     : 'Edit',
      LB_HIDE_FILTERS             : 'Hide Advanced Filters',
      LB_SHOW_FILTERS             : 'Show Advanced Filters',
      MSG_ARE_YOU_SURE            : 'Are you sure?'
    };
  }

  $(document).ready(function() {
    function disableForm(form) {
      $('input, select', form)
        .prop('disabled', 'disabled')
        .addClass('disabled');
    }

    function enableForm(form) {
      $('input, select', form)
        .prop('disabled', null)
        .removeClass('disabled');
    }

    $('#luser-wrapper form .o-btn-submit').on('click', function(e) {
      e.preventDefault();

      var self = $(this);
      var form = $(self.parents('form').get(0));

      var qtyField = $('#qty');
      var dataQty = parseInt(qtyField.val());
      if (
        isNaN(dataQty)
        || dataQty < 0
        || dataQty > 5000
      ) {
        qtyField.trigger('focus');
        return;
      }

      var dataGender = parseInt($('#gender').val());
      dataGender = !isNaN(dataGender) ? dataGender : 0;

      var dataSeed = ($('#seed').val() || "").trim();
      var dataNationalities = $('#nationalities').val() || [];
      var dataRole = $('#role').val() || '';
      var table = $('.c-rowset');
      var resultsTitle = $('.o-rowset-title');

      var skipUsersReview = $('#skip_review').is(':checked') ? 1 : 0;

      $.ajax({
        type: 'GET',
        url : ajaxurl,
        data: {
          action     : 'luser:generate_users',
          nonce      : self.data('nonce'),
          qty        : dataQty,
          gender     : dataGender,
          seed       : dataSeed,
          nat        : dataNationalities.join(','),
          role       : dataRole,
          skip_review: skipUsersReview
        },
        beforeSend: function() {
          self.text($i18n.LB_GENERATING);
          self.addClass('disabled');
          resultsTitle.text($i18n.MSG_RESULTS_WILL_APPEAR_HERE).show();

          table.addClass('u-hidden');
          $('tbody', table).html('');
          disableForm(form);

          $('#table-actions').addClass('u-hidden');
          $('[data-action="select_all"]', table).prop('checked', false);
        },
        success: function(response) {
          self.text($i18n.LB_GENERATE);

          if (response.success) {
            resultsTitle.text($i18n.LB_RESULTS.replace('%d', response.results_count));

            $('tbody', table).html($(response.data));
            table.removeClass('u-hidden');

            $('select', table).select2();

            $('#table-actions').removeClass('u-hidden');
          } else {
            resultsTitle.hide();

            alert(response.error);
          }
        },
        error: function(request, textStatus, err) {
          alert(err);
        },
        complete: function() {
          self.removeClass('disabled');
          enableForm(form);
        }
      });
    });

    $('.c-rowset').on('click', '[data-action="user.add"]', function(e) {
      e.preventDefault();

      var self = $(this);
      var row = $(self.parents('tr'));

      var userRoleField = $('[data-column="role"]', row);
      var selectedUserRoleValue = userRoleField.val();
      var controlsWrapper = $('td:last-child', row);
      var controlsButtonsHtml = controlsWrapper.html();

      $.ajax({
        type: 'POST',
        url : ajaxurl,
        data: {
          action    : 'luser:add_user',
          nonce     : self.data('nonce'),
          i         : row.data('index'),
          user_fname: $('[data-column="first_name"]', row).text(),
          user_lname: $('[data-column="last_name"]', row).text(),
          user_uname: $('[data-column="username"]', row).text(),
          user_email: $('[data-column="email"]', row).text(),
          user_pwd  : $('[data-column="password"]', row).text(),
          user_role : selectedUserRoleValue
        },
        beforeSend: function() {
          controlsWrapper.text($i18n.LB_ADDING);
        },
        success: function(response) {
          if (!response.success) {
            if (!response.error) {
              console.error($i18n.MSG_UNDOCUMMENTED_ERROR);
            } else {
              console.error(response.error);
            }
          } else {
            var selectedUserRoleText = $('option[value="'+ selectedUserRoleValue +'"]', userRoleField).text();
            var userRoleFieldParent = $('[data-column="role"]', row).parent();
            userRoleFieldParent.text(selectedUserRoleText);

            var editAnchor = $('<a></a>', {
              target: '_blank',
              class : '',
              href  : response.user_profile_url
            });

            editAnchor.html($i18n.LB_EDIT);

            controlsWrapper.html(editAnchor);

            $('> td:first-child', row).html('');
          }
        },
        error: function() {
          controlsWrapper.html($(controlsButtonsHtml));
        }
      });
    });

    $('.c-rowset').on('click', '[data-action="user.discard"]', function(e) {
      $(this).parents('tr[data-index]').remove();

      var table = $('.c-rowset');
      var rows = $('tbody tr', table);
      if (rows.length === 0) {
        table.addClass('u-hidden');
        $('.o-rowset-title').text('');
        $('#table-actions').addClass('u-hidden');
      } else {
        $('.o-rowset-title').text($i18n.LB_RESULTS.replace('%d', rows.length));
      }
    });

    $('#results-wrapper').on('click', '[data-action="users.select_all"]', function(e) {
      var table = $('.c-rowset');
      var cbxs = $('tbody input[type="checkbox"]', table);
      if (cbxs.length > 0) {
        cbxs.prop('checked', $(this).prop('checked'));
      }
    });

    $('#luser-wrapper select').select2({
      width: '100%'
    });

    $('#luser-wrapper').on('click', '[data-action="filters.show"]', function(e) {
      e.preventDefault();

      var self = $(this);

      var filtersWrapper = $('.c-advanced-filters');
      filtersWrapper.css('height', $('tbody', filtersWrapper).height() + 'px');
      filtersWrapper.addClass('s-expanded');

      self.attr('data-action', 'filters.hide');
      self.text($i18n.LB_HIDE_FILTERS);
    });

    $('#luser-wrapper').on('click', '[data-action="filters.hide"]', function(e) {
      e.preventDefault();

      var self = $(this);

      var filtersWrapper = $('.c-advanced-filters');
      filtersWrapper.css('height', null);
      filtersWrapper.removeClass('s-expanded');

      self.attr('data-action', 'filters.show');
      self.text($i18n.LB_SHOW_FILTERS);
    });

    $('#bulk-actions').on('change', function() {
      var self = $(this);
      var action = self.val() || null;

      if (!action) return;

      var selectedItems = $('#results-wrapper table .o-table-control-cbx:checked');
      if (selectedItems.length > 0) {
        if (['add', 'discard'].indexOf(action) >= 0) {
          var confirmation = confirm($i18n.MSG_ARE_YOU_SURE);
          if (!confirmation) return;

          selectedItems.each(function() {
            var row = $($(this).parents('tr[data-index]'));

            var actionBtn = $('[data-action="user.'+ action +'"]', row);
            if (actionBtn.length > 0) {
              actionBtn.trigger('click');
            }
          });
        }
      }

      self.select2('val', 0);
    });

    $('#results-wrapper .c-rowset thead input[data-action="select_all"]').on('click', function() {
      var self = $(this);
      var table = $(self.parents('.c-rowset'));

      var currentState = self.is(':checked');
      $('> tbody td .o-table-control-cbx', table).prop('checked', currentState);
    });
  });
})(
  window,
  window.document,
  typeof jQuery !== 'undefined' ? jQuery : null,
  typeof $i18n !== 'undefined' ? $i18n : null
);
