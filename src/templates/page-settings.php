<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
  <h1><?php _e('Lorem User Generator Settings', 'lorem-user-generator'); ?></h1>
  <form method="POST" action="options.php">
    <?php settings_fields('lorem-user-generator'); ?>
    <?php do_settings_sections('lorem-user-generator'); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="lorem-user-generator:default_user_role"><?php _e('Default User Role', 'lorem-user-generator'); ?></label>
        </th>
        <td>
          <select id="lorem-user-generator:default_user_role" name="lorem-user-generator:default_user_role">
            <?php foreach ($usersRoles as $userRoleSlug => $userRole): ?>
            <option value="<?php echo $userRoleSlug; ?>"<?php echo $userRoleSlug === $defaultUserRole ? ' selected' : ''; ?>><?php echo esc_attr($userRole['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
