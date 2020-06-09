<?php
if (!defined('ABSPATH')) exit;

$actualDefaultRole = $actualDefaultRole ?? '';
$nonce = $nonce ?? '';
$existentEditableUserRoles = $existentEditableUserRoles ?? [];
?>

<main class="wrap">
    <h1>Lorem User Generator Settings</h1>
    <form method="POST" action="options.php">
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="lorem-user-generator:default_user_role">Default Gender</label>
                </th>
                <td>
                    <select id="lorem-user-generator:default_user_role" name="lorem-user-generator:default_user_role">
                        <?php foreach ($existentEditableUserRoles as $userRoleSlug => $userRoleTitle): ?>
                        <option value="<?php echo $userRoleSlug; ?>" <?php echo $userRoleSlug === $actualDefaultRole ? 'selected' : ''; ?>><?php echo esc_attr($userRoleTitle); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <button type="submit" class="button button-primary">Save Changes</button>
        <input type="hidden" name="option_page" value="lorem-user-generator" />
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
    </form>
</main>
