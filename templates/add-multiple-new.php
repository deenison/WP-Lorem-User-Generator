<?php if (!defined('ABSPATH')) exit; ?>

<main id="lorem-user-generator" class="wrap">
    <h1><?php esc_html_e('Add New Multiple Users', 'lorem-user-generator'); ?></h1>
    <p><?php esc_html_e('Generate & add new users with random data and add them to this site.', 'lorem-user-generator'); ?></p>
    <form id="form-fetch" class="s-border-top">
        <fieldset>
            <h3 class="legend"><?php esc_html_e('Preferences', 'lorem-user-generator'); ?></h3>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="pref-quantity">
                                <?php esc_html_e('Users Quantity', 'lorem-user-generator'); ?>
                                <span class="description"><?php esc_html_e('(required)'); ?></span>
                            </label>
                        </th>
                        <td>
                            <input
                                id="pref-quantity"
                                name="quantity"
                                type="number"
                                min="1"
                                max="25"
                                step="1"
                                value="1"
                                style="max-width: 60px;"
                            />
                            <p class="description"><?php esc_html_e('You can generate up to 25 users per request.', 'lorem-user-generator'); ?></p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="pref-gender"><?php esc_html_e('Gender', 'lorem-user-generator'); ?></label>
                        </th>
                        <td>
                            <select id="pref-gender" name="gender">
                                <option value=""><?php esc_html_e('Both', 'lorem-user-generator'); ?></option>
                                <option value="male"><?php esc_html_e('Male', 'lorem-user-generator'); ?></option>
                                <option value="female"><?php esc_html_e('Female', 'lorem-user-generator'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="pref-nationalities"><?php esc_html_e('Nationalities', 'lorem-user-generator'); ?></label>
                        </th>
                        <td>
                            <select id="pref-nationalities" name="nationalities" multiple>
                                <option value="au"><?php esc_html_e('Australia', 'lorem-user-generator'); ?></option>
                                <option value="br"><?php esc_html_e('Brazil', 'lorem-user-generator'); ?></option>
                                <option value="ca"><?php esc_html_e('Canada', 'lorem-user-generator'); ?></option>
                                <option value="ch"><?php esc_html_e('Switzerland', 'lorem-user-generator'); ?></option>
                                <option value="de"><?php esc_html_e('Germany', 'lorem-user-generator'); ?></option>
                                <option value="dk"><?php esc_html_e('Denmark', 'lorem-user-generator'); ?></option>
                                <option value="es"><?php esc_html_e('Spain', 'lorem-user-generator'); ?></option>
                                <option value="fi"><?php esc_html_e('Finland', 'lorem-user-generator'); ?></option>
                                <option value="fr"><?php esc_html_e('France', 'lorem-user-generator'); ?></option>
                                <option value="gb"><?php esc_html_e('Great Britain', 'lorem-user-generator'); ?></option>
                                <option value="ie"><?php esc_html_e('Ireland', 'lorem-user-generator'); ?></option>
                                <option value="ir"><?php esc_html_e('Iran', 'lorem-user-generator'); ?></option>
                                <option value="no"><?php esc_html_e('Norway', 'lorem-user-generator'); ?></option>
                                <option value="nl"><?php esc_html_e('Netherlands', 'lorem-user-generator'); ?></option>
                                <option value="nz"><?php esc_html_e('New Zealand', 'lorem-user-generator'); ?></option>
                                <option value="tr"><?php esc_html_e('Turkey', 'lorem-user-generator'); ?></option>
                                <option value="us"><?php esc_html_e('United States', 'lorem-user-generator'); ?></option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <br/>
        <button type="submit" class="button button-primary"><?php esc_html_e('Generate', 'lorem-user-generator'); ?></button>
    </form>
    <div id="output-console-wrapper" style="display: none;">
        <pre><strong><?php esc_html_e('CONSOLE', 'lorem-user-generator'); ?>:</strong> <span id="output-console-content"></span></pre>
    </div>
    <div class="table-wrapper">
        <form id="form-save-users">
            <table id="users-results">
                <thead>
                    <tr>
                        <th class="u-text-center" style="width: 25px;">#</th>
                        <th style="width: 150px;"><?php _e('First Name'); ?></th>
                        <th style="width: 150px;"><?php _e('Last Name'); ?></th>
                        <th style="width: 225px;"><?php _e('Email'); ?></th>
                        <th style="width: 125px;"><?php _e('Username'); ?></th>
                        <th style="width: 125px;"><?php _e('Password'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6"><?php esc_html_e('Please, adjust your parameters first.', 'lorem-user-generator'); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="notes-wrapper">
                <p><?php esc_html_e('Passwords are displayed only once. Write them down!', 'lorem-user-generator'); ?></p>
            </div>
            <table class="form-table" role="presentation">
                <tbody>
                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="pref-role">
                            <?php esc_html_e('Role'); ?>
                            <span class="description"><?php esc_html_e('(required)'); ?></span>
                        </label>
                    </th>
                    <td>
                        <select id="pref-role" name="role" class="disabled" disabled>
                            <?php foreach ($usersRoles as $userRoleSlug => $userRoleTitle): ?>
                                <option value="<?php echo $userRoleSlug; ?>" <?php echo $userRoleSlug === $defaultUserRole ? 'selected' : ''; ?>><?php echo $userRoleTitle; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div>
        <button
            type="submit"
            class="button button-primary disabled"
            form="form-save-users"
            disabled
        ><?php esc_html_e('Add user(s)', 'lorem-user-generator'); ?></button>
    </div>
</main>
