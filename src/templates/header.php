<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

use \LoremUserGenerator\Helper;
?>

<div>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row">
          <label for="qty"><?php echo __('Number of users', 'lorem-user-generator'); ?> *</label></br>
          <small><?php printf('(* %s)', __('required', 'lorem-user-generator')); ?></small>
        </th>
        <td>
          <input type="number" id="qty" name="qty" class="o-input" step="1" min="1" max="5000">
          <p class="description">
            <?php _e('You can generate up to 5000 users per batch.', 'lorem-user-generator'); ?><br>
            <?php _e('Higher the number, the longer will take to generate your results.', 'lorem-user-generator'); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  <a href="#" data-action="filters.show"><?php _e('Show Advanced Filters', 'lorem-user-generator'); ?></a>
  <table class="form-table u-hidden c-advanced-filters">
    <tbody>
      <tr>
        <th scope="row">
          <label for="gender"><?php _e('Gender', 'lorem-user-generator'); ?></label>
        </th>
        <td>
          <select id="gender" name="gender">
            <option value="0"><?php _e('Both', 'lorem-user-generator'); ?></option>
            <option value="1"><?php _e('Male', 'lorem-user-generator'); ?></option>
            <option value="2"><?php _e('Female', 'lorem-user-generator'); ?></option>
          </select>
          <p class="description"><?php _e('Specify whether you would like to have only male or only female users generated.', 'lorem-user-generator'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label for="seed"><?php _e('Seed', 'lorem-user-generator'); ?></label>
        </th>
        <td>
          <input type="text" id="seed" name="seed" class="o-input">
          <p class="description"><?php _e('Seeds can be any string or sequence of characters. They allow you to always generate the same set of users.', 'lorem-user-generator'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label><?php _e('Nationalities', 'lorem-user-generator'); ?></label>
        </th>
        <td>
          <?php $nationalities = Helper::getNationalities(); ?>
          <select id="nationalities" name="nationalities[]" multiple="multiple">
            <?php foreach ($nationalities as $iso => $countryName): ?>
            <option value="<?php echo $iso; ?>"><?php echo $countryName; ?></option>
            <?php endforeach; ?>
          </select>
          <p class="description"><?php _e('Specify what nationalities you would like the users set to belongs to.', 'lorem-user-generator'); ?></p>
        </td>
      </tr>
    </tbody>
  </table>

  <button type="button" class="button button-primary o-btn-submit" data-nonce="<?php echo wp_create_nonce(LUG_SLUG . '.generate_users'); ?>"><?php _e('Generate', 'lorem-user-generator'); ?></button>
</div>
