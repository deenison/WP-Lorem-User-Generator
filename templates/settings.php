<?php
if (!defined('ABSPATH')) exit;

$nonce = $nonce ?? '';
?>

<main class="wrap">
    <h1><?php esc_html_e('Lorem User Generator Settings', 'lorem-user-generator'); ?></h1>
    <form method="POST" action="options.php">
        <table class="form-table" role="presentation">

        </table>
        <button type="submit" class="button button-primary"><?php esc_html_e('Save Changes', 'lorem-user-generator'); ?></button>
        <input type="hidden" name="option_page" value="lorem-user-generator" />
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
    </form>
</main>
