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
                $skipUsersReview = isset($_GET['skip_review']) && is_numeric($_GET['skip_review']) ? (bool)$_GET['skip_review'] : false;

                if ($skipUsersReview) {
                    foreach ($data as $userIndex => $user) {
                        $userData = array(
                            'fname' => $user->name->first,
                            'lname' => $user->name->last,
                            'email' => $user->email,
                            'uname' => $user->login->username,
                            'pwd'   => $user->login->password,
                            'role'  => $role
                        );

                        $userId = self::insertUserToDb($userData, $usersRoles);

                        $dataHTML .= View::render('result-row', array(
                            'user'            => $user,
                            'userIndex'       => $userIndex,
                            'usersRoles'      => $usersRoles,
                            'defaultUserRole' => $role,
                            'userId'          => $userId
                        ), true);
                    }
                } else {
                    foreach ($data as $userIndex => $user) {
                        $dataHTML .= View::render('result-row', array(
                            'user'            => $user,
                            'userIndex'       => $userIndex,
                            'usersRoles'      => $usersRoles,
                            'defaultUserRole' => $role
                        ), true);
                    }
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

            $userData = array(
                'fname' => isset($_POST['user_fname']) ? trim((string)$_POST['user_fname']) : '',
                'lname' => isset($_POST['user_lname']) ? trim((string)$_POST['user_lname']) : '',
                'email' => isset($_POST['user_email']) ? trim((string)$_POST['user_email']) : '',
                'uname' => isset($_POST['user_uname']) ? trim((string)$_POST['user_uname']) : '',
                'pwd'   => isset($_POST['user_pwd']) ? trim((string)$_POST['user_pwd']) : '',
                'role'  => isset($_POST['user_role']) ? trim((string)$_POST['user_role']) : ''
            );

            $userId = self::insertUserToDb($userData);
            if (is_string($userId)) {
                throw new Exception($userId);
            }

            $response['user_role'] = $userData['role'];
            $response['user_profile_url'] = admin_url('user-edit.php?user_id=' . $userId);

            $response['success'] = true;
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        wp_send_json($response);
    }

    /**
     * Add a set of user data into DB as a new user.
     *
     * @since   @todo
     * @access  private
     * @static
     *
     * @param   mixed   $data       Can be either plain object or array.
     * @param   array   $userRoles  Array of available user roles.
     *
     * @return  The new user id in case of success. Error string otherwise.
     */
    private static function insertUserToDb($data, $userRoles = array())
    {
        $data = json_decode(json_encode((array)$data));

        if (empty($userRoles)) {
            $userRoles = Helper::getUsersRoles();
        }

        $badParamErrTmpl = _x('Missing/invalid "%s" parameter.', '%s: parameter name', 'lorem-user-generator');

        $userData = array();

        $requiredColumns = array('fname', 'lname', 'email', 'uname', 'pwd', 'role');
        foreach ($requiredColumns as $columnName) {
            $columnValue = isset($data->{$columnName}) ? trim((string)$data->{$columnName}) : '';
            if (empty($columnValue)) {
                throw new \Exception(sprintf($badParamErrTmpl, $columnName));
            }

            $data->{$columnName} = $columnValue;
        }

        if (!isset($userRoles[$data->role])) {
            throw new \Exception(sprintf($badParamErrTmpl, 'role'));
        }

        $userData = apply_filters('lorem-user-generator:onBeforeAddUser', array(
            'user_login'    => $data->uname,
            'user_pass'     => $data->pwd,
            'user_nicename' => $data->uname,
            'user_email'    => $data->email,
            'display_name'  => $data->fname . ' ' . $data->lname,
            'nickname'      => $data->uname,
            'first_name'    => $data->fname,
            'last_name'     => $data->lname,
            'role'          => $data->role
        ));

        unset($columnName, $requiredColumns, $data);

        $userId = wp_insert_user($userData);
        if (is_wp_error($userId)) {
            throw new \Exception($userId->get_error_message());
        }

        return $userId;
    }
}
