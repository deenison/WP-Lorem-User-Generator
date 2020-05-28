<main id="lorem-user-generator" class="wrap">
    <h1>Add New Multiple Users</h1>
    <p>Generate & add new users with random data and add them to this site.</p>
    <form id="form-fetch" class="s-border-top">
        <fieldset>
            <h3 class="legend">Preferences</h3>
            <div>
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
        </fieldset>
        <br/>
        <button type="submit" class="button button-primary">Generate</button>
    </form>
    <div id="output-console-wrapper" style="display: none;">
        <pre><strong>CONSOLE:</strong> <span id="output-console-content"></span></pre>
    </div>
    <div class="table-wrapper">
        <form id="form-save-users">
            <table>
                <thead>
                    <tr>
                        <th style="width: 150px;">First Name</th>
                        <th style="width: 150px;">Last Name</th>
                        <th style="width: 225px;">Email</th>
                        <th style="width: 125px;">Username</th>
                        <th style="width: 125px;">Password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">Please, adjust your parameters first.</td>
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

