<?php if (!defined('ABSPATH')) exit; ?>

<main id="lorem-user-generator" class="wrap">
    <h1>Add New Multiple Users</h1>
    <p>Generate & add new users with random data and add them to this site.</p>
    <form id="form-fetch" class="s-border-top">
        <fieldset>
            <h3 class="legend">Preferences</h3>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="pref-quantity">
                                Users Quantity
                                <span class="description">(required)</span>
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
                            <p class="description">You can generate up to 25 users per request.</p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="pref-gender">Gender</label>
                        </th>
                        <td>
                            <select id="pref-gender" name="gender">
                                <option value="">Both</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="pref-nationalities">Nationalities</label>
                        </th>
                        <td>
                            <select id="pref-nationalities" name="nationalities" multiple>
                                <option value="au">Australia</option>
                                <option value="br">Brazil</option>
                                <option value="ca">Canada</option>
                                <option value="ch">Switzerland</option>
                                <option value="de">Germany</option>
                                <option value="dk">Denmark</option>
                                <option value="es">Spain</option>
                                <option value="fi">Finland</option>
                                <option value="fr">France</option>
                                <option value="gb">Great Britain</option>
                                <option value="ie">Ireland</option>
                                <option value="ir">Iran</option>
                                <option value="no">Norway</option>
                                <option value="nl">Netherlands</option>
                                <option value="nz">New Zealand</option>
                                <option value="tr">Turkey</option>
                                <option value="us">United States</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <br/>
        <button type="submit" class="button button-primary">Generate</button>
    </form>
    <div id="output-console-wrapper" style="display: none;">
        <pre><strong>CONSOLE:</strong> <span id="output-console-content"></span></pre>
    </div>
    <div class="table-wrapper">
        <form id="form-save-users">
            <table id="users-results">
                <thead>
                    <tr>
                        <th class="u-text-center" style="width: 25px;">#</th>
                        <th style="width: 150px;">First Name</th>
                        <th style="width: 150px;">Last Name</th>
                        <th style="width: 225px;">Email</th>
                        <th style="width: 125px;">Username</th>
                        <th style="width: 125px;">Password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">Please, adjust your parameters first.</td>
                    </tr>
                </tbody>
            </table>
            <div class="notes-wrapper">
                <p>Passwords are displayed only once. Write them down!</p>
            </div>
            <table class="form-table" role="presentation">
                <tbody>
                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="pref-role">
                            Role
                            <span class="description">(required)</span>
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
        >Add them all</button>
    </div>
</main>
