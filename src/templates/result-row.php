<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;
?>

<tr data-index="<?php echo $userIndex; ?>">
  <td class="u-text-center">
    <input type="checkbox" class="o-table-control-cbx" value="<?php echo $userIndex; ?>">
  </td>
  <td class="u-text-center"><?php echo ++$userIndex; ?></td>
  <td class="u-text-left">
    <span data-column="first_name"><?php echo ucfirst($user->name->first); ?></span>&nbsp;<span data-column="last_name"><?php echo ucfirst($user->name->last); ?></span>
  </td>
  <td class="u-text-left" data-column="email" data-value="<?php echo $user->email; ?>"><?php echo $user->email; ?></td>
  <td class="u-text-left" data-column="username" data-value="<?php echo $user->login->username; ?>"><?php echo $user->login->username; ?></td>
  <td class="u-text-left">
    <code data-column="password"><?php echo $user->login->password; ?></code>
  </td>
  <td class="u-text-left">
    <select data-column="role">
      <?php foreach ($usersRoles as $userRoleSlug => $userRole): ?>
      <option value="<?php echo $userRoleSlug; ?>"<?php echo $userRoleSlug === $defaultUserRole ? ' selected' : ''; ?>><?php echo $userRole['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </td>
  <td>
    <button type="button" class="button" title="<?php _e('Discard User', 'lorem-user-generator'); ?>" data-action="user.discard">
      <span class="dashicons dashicons-trash"></span>
    </button>
    <button type="button" class="button button-primary" title="<?php _e('Add User'); ?>" data-action="user.add" data-nonce="<?php echo wp_create_nonce(LUG_SLUG . ':nonce.add_user:' . ($userIndex - 1)); ?>">
      <span class="dashicons dashicons-plus"></span>
    </button>
  </td>
</tr>
