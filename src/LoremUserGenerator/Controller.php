<?php
namespace LoremUserGenerator;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

use \LoremUserGenerator\Plugin;
use \LoremUserGenerator\View;
use \LoremUserGenerator\Helper;

/**
 * @package     LoremUserGenerator
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 * @final
 */
final class Controller
{
    /**
     * Render the plugin main page.
     *
     * @since   1.0.0
     * @static
     */
    public static function renderPluginPage()
    {
        View::render('page');
    }

    /**
     * Render the plugin settings page.
     *
     * @since   1.0.0
     * @static
     */
    public static function renderSettingsPage()
    {
        View::render('page-settings', array(
            'defaultUserRole' => Helper::getDefaultUserRole(),
            'usersRoles'      => Helper::getUsersRoles()
        ));
    }

    /**
     * AJAX endpoint that send a GET request to the generator API to retrieve random dummy users.
     *
     * @since   1.0.0
     * @static
     */
    public static function generateRandomUsers()
    {
        header('Content-Type: application/json');

        $response = array(
            'success' => false,
            'data'    => array(),
            'error'   => null
        );

        try {
            if (
                !defined('DOING_AJAX')
                || !DOING_AJAX
                || empty($_GET)
                || !isset($_GET['nonce'])
                || !check_ajax_referer(LUG_SLUG . '.generate_users', 'nonce', false)
            ) {
                throw new \Exception(__('Invalid request.', 'lorem-user-generator'));
            }

            $params = array();
            if (!isset($_GET['qty']) || !is_numeric($_GET['qty'])) {
                throw new \Exception(sprintf(__('Invalid "%s" parameter.', 'lorem-user-generator'), 'qty'));
            } else {
                $params['qty'] = (int)$_GET['qty'];
            }

            if (isset($_GET['gender']) && is_numeric($_GET['gender'])) {
                $gender = (int)$_GET['gender'];

                if ($gender === 1) {
                    $params['gender'] = 'male';
                } else if ($gender === 2) {
                    $params['gender'] = 'female';
                }

                unset($gender);
            }

            $customSeed = isset($_GET['seed']) ? trim($_GET['seed']) : "";;
            if (strlen($customSeed) > 0) {
                $params['seed'] = $customSeed;
            }
            unset($customSeed);

            if (isset($_GET['nat']) && !empty($_GET['nat'])) {
                $nationalitiesPool = Helper::getNationalities();

                $params['nationalities'] = array();

                $nationalities = explode(',', $_GET['nat']);
                foreach ($nationalities as $iso) {
                    if (!isset($nationalitiesPool[$iso])) {
                        continue;
                    }

                    $params['nationalities'][] = $iso;
                }
                unset($iso, $nationalities, $nationalitiesPool);
            }

            $role = isset($_GET['role']) ? trim($_GET['role']) : '';
            $usersRoles = Helper::getUsersRoles();
            if (!isset($usersRoles[$role])) {
                throw new Exception(__('Invalid user role.', 'lorem-user-generator'));
            }

            $data = Helper::fetchDataFromApi($params);
            $data = isset($data->results) ? $data->results : array();

            $dataHTML = "";

            if (count($data) > 0) {
                foreach ($data as $userIndex => $user) {
                    $dataHTML .= View::render('result-row', array(
                        'user'            => $user,
                        'userIndex'       => $userIndex,
                        'usersRoles'      => $usersRoles,
                        'defaultUserRole' => $role
                    ), true);
                }
            }

            $response['results_count'] = count($data);
            $response['data'] = trim($dataHTML);
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        wp_send_json($response);
    }

    /**
     * AJAX endpoint that attempt to add a new user.
     *
     * @since   1.0.0
     * @static
     */
    public static function addUser()
    {
        header('Content-Type: application/json');

        $response = array(
            'success' => false,
            'error'   => null
        );

        try {
            if (
                !defined('DOING_AJAX')
                || !DOING_AJAX
                || empty($_POST)
                || !isset($_POST['nonce'])
            ) {
                throw new \Exception(__('Invalid request.', 'lorem-user-generator'));
            }

            $userIndex = isset($_POST['i']) && is_numeric($_POST['i']) ? (int)$_POST['i'] : -1;
            if (!check_ajax_referer(LUG_SLUG . ':nonce.add_user:' . $userIndex, 'nonce', false)) {
                throw new \Exception(__('Invalid nonce.', 'lorem-user-generator'));
            }

            $missingParamErrorString = _x('Missing "%s" parameter.', '%s: parameter name', 'lorem-user-generator');

            $userFirstName = isset($_POST['user_fname']) ? trim((string)$_POST['user_fname']) : "";
            if (empty($userFirstName)) {
                throw new \Exception(sprintf($missingParamErrorString, 'user_fname'));
            }

            $userLastName = isset($_POST['user_lname']) ? trim((string)$_POST['user_lname']) : "";
            if (empty($userLastName)) {
                throw new \Exception(sprintf($missingParamErrorString, 'user_lname'));
            }

            $userEmail = isset($_POST['user_email']) ? trim((string)$_POST['user_email']) : "";
            if (empty($userEmail)) {
                throw new \Exception(sprintf($missingParamErrorString, 'user_email'));
            }

            $userUsername = isset($_POST['user_uname']) ? trim((string)$_POST['user_uname']) : "";
            if (empty($userUsername)) {
                throw new \Exception(sprintf($missingParamErrorString, 'user_uname'));
            }

            $userPassword = isset($_POST['user_pwd']) ? (string)$_POST['user_pwd'] : "";
            if (empty($userPassword)) {
                throw new \Exception(sprintf($missingParamErrorString, 'user_pwd'));
            }

            $userRole = isset($_POST['user_role']) ? trim((string)$_POST['user_role']) : "";
            $usersRoles = Helper::getUsersRoles();
            if (!isset($usersRoles[$userRole])) {
                throw new \Exception(sprintf(__('Invalid "%s" parameter.', 'lorem-user-generator'), 'user_role'));
            }

            $userData = apply_filters('lorem-user-generator:onBeforeAddUser', array(
                'user_login'    => $userUsername,
                'user_pass'     => $userPassword,
                'user_nicename' => $userUsername,
                'user_email'    => $userEmail,
                'display_name'  => $userFirstName . ' ' . $userLastName,
                'nickname'      => $userUsername,
                'first_name'    => $userFirstName,
                'last_name'     => $userLastName,
                'role'          => $userRole
            ));

            $userId = wp_insert_user($userData);
            if (is_wp_error($userId)) {
                throw new \Exception($userId->get_error_message());
            }

            $response['user_role'] = $userRole;
            $response['user_profile_url'] = admin_url('user-edit.php?user_id=' . $userId);

            $response['success'] = true;
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        wp_send_json($response);
    }
}
