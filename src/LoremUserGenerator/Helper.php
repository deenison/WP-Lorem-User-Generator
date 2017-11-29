<?php
namespace LoremUserGenerator;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @package     LoremUserGenerator
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 * @final
 */
final class Helper
{
    /**
     * Retrieve all current user roles.
     *
     * @since   1.0.0
     * @static
     *
     * @return  array
     */
    public static function getUsersRoles()
    {
        if (!function_exists('get_editable_roles')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }

        $editableRoles = get_editable_roles();

        foreach ($editableRoles as &$role) {
            $role['name'] = translate_user_role($role['name']);
        }

        return $editableRoles;

    }

    /**
     * Return all currently supported nationalities by the API.
     *
     * @since   1.0.0
     * @static
     *
     * @see     https://randomuser.me/documentation#nationalities
     */
    public static function getNationalities()
    {
        $nationalities = array(
            'au' => __('Australia'),
            'br' => __('Brazil'),
            'ca' => __('Canada'),
            'ch' => __('Switzerland'),
            'de' => __('Germany'),
            'dk' => __('Denmark'),
            'es' => __('Spain'),
            'fi' => __('Finland'),
            'fr' => __('France'),
            'gb' => __('United Kingdom'),
            'ie' => __('Ireland'),
            'ir' => __('Iran, Islamic Republic of'),
            'nl' => __('Netherlands'),
            'nz' => __('New Zealand'),
            'tr' => __('Turkey'),
            'us' => __('United States')
        );

        return apply_filters('lorem-user-generator:getNationalities', $nationalities);
    }

    /**
     * Send a GET request with custom parameters to the random generator API.
     *
     * @since   1.0.0
     * @static
     *
     * @see     https://randomuser.me
     *
     * @param   array
     *
     * @return  object
     */
    public static function fetchDataFromApi($customParams = array())
    {
        $defaultParams = array(
            // Number of users to be generated.
            'qty'           => 1,
            // Custom seed.
            'seed'          => null,
            // Specified nationalities. The options available can be accessed via Helper::getNationalities() function.
            'nationalities' => null,
            // Specified gender. Either "male" or "female".
            'gender'        => null
        );

        $params = array(
            'inc=name,login,email'
        );
        $customParams = (object)array_merge($defaultParams, $customParams);

        if (
            !is_numeric($customParams->qty)
            || (int)$customParams->qty < 0
            || (int)$customParams->qty > 5000
        ) {
            $customParams->qty = 1;
        }

        $params[] = 'results=' . $customParams->qty;

        if (!empty($customParams->gender)) {
            $gender = strtolower($customParams->gender);

            if ($gender !== "male" && $gender !== "female") {
                throw new \Exception(sprintf(__('Invalid "%s" parameter.', 'lorem-user-generator'), 'gender'));
            }

            $params[] = 'gender=' . $gender;

            unset($gender);
        }

        if (strlen($customParams->seed) > 0) {
            $params[] = 'seed=' . $customParams->seed;
        }

        if (!empty($customParams->nationalities)) {
            $nationalities = !is_array($customParams->nationalities) ? (array)$customParams->nationalities : $customParams->nationalities;

            $params[] = 'nat=' . implode(',', $nationalities);

            unset($nationalities);
        }

        $params = apply_filters('lorem-user-generator:setRequestParams', $params);

        $endpoint = sprintf('%s?%s', LUG_RANDOM_API_URL, implode('&', $params));

        $request = wp_remote_get($endpoint);
        if (is_wp_error($request)) {
            throw new \Exception($request->get_error_message());
        }

        $response = wp_remote_retrieve_body($request);
        if (empty($response)) {
            throw new \Exception(__('There was some error retrieving the response body.', 'lorem-user-generator'));
        }

        $response = json_decode($response);
        if (empty($response)) {
            throw new \Exception(__('There was some error parsing the response body.', 'lorem-user-generator'));
        }

        return apply_filters('lorem-user-generator:fetchDataFromApi', $response);
    }
}
