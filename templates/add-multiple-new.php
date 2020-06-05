<main id="lorem-user-generator" class="wrap">
    <h1>Add New Multiple Users</h1>
    <p>Generate & add new users with random data and add them to this site.</p>
    <form id="form-fetch" class="s-border-top">
        <fieldset>
            <h3 class="legend">Preferences</h3>
            <div class="input-wrapper">
                <label for="pref-quantity">Quantity</label>
                <input
                    id="pref-quantity"
                    name="quantity"
                    type="number"
                    min="1"
                    max="25"
                    step="1"
                    value="1"
                />
            </div>
            <div class="input-wrapper">
                <label for="pref-gender">Gender</label>
                <select id="pref-gender" name="gender">
                    <option value="">Both</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="input-wrapper">
                <label for="pref-nationalities">Nationalities</label>
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
            </div>
        </fieldset>
        <br/>
        <button type="submit" class="button button-primary">Generate</button>
        <div id="notes-wrapper">
            <p>You can generate up to 25 users per request.</p>
            <p>Passwords are displayed only once. Write them down!</p>
        </div>
    </form>
    <div id="output-console-wrapper" style="display: none;">
        <pre><strong>CONSOLE:</strong> <span id="output-console-content"></span></pre>
    </div>
    <div class="table-wrapper">
        <form id="form-save-users">
            <table>
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
            <div class="input-wrapper">
                <label for="pref-role">Role</label>
                <select id="pref-role" name="role">
                    <?php foreach ($usersRoles as $userRoleSlug => $userRoleTitle): ?>
                        <option value="<?php echo $userRoleSlug; ?>" <?php echo $userRoleSlug === $defaultUserRole ? 'selected' : ''; ?>><?php echo $userRoleTitle; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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

