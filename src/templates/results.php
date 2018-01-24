<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;
?>

<p>
  <span class="dashicons dashicons-info"></span>
  <?php _e('You will not see any of the passwords displayed here again, so write them down or make sure to change them later.', 'lorem-user-generator'); ?>
</p>
<hr>
<div id="results-wrapper">
  <h2 class="o-rowset-title"></h2>
  <div id="table-actions" class="u-hidden">
    <select id="bulk-actions" data-placeholder="<?php _e('Bulk Actions'); ?>">
      <option></option>
      <option value="add"><?php _e('Add all selected users', 'lorem-user-generator'); ?></option>
      <option value="discard"><?php _e('Discard all selected users', 'lorem-user-generator'); ?></option>
    </select>
  </div>
  <table class="c-rowset u-hidden">
    <thead>
      <tr>
        <th class="u-text-center">
          <input type="checkbox" data-action="select_all">
        </th>
        <th class="u-text-center">#</th>
        <th class="u-text-left"><?php _e('Name'); ?></th>
        <th class="u-text-left"><?php _e('Email'); ?></th>
        <th class="u-text-left"><?php _e('Username'); ?></th>
        <th class="u-text-left"><?php _e('Password'); ?></th>
        <th class="u-text-left"><?php _e('Role'); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
