<?php
namespace LoremUserGenerator\Tests;

class UsersTest extends \WP_Ajax_UnitTestCase
{
    public function testAjaxCall()
    {
        $this->_setRole('administrator');

        $batchSize = 3;

        $selectedUserIndex = mt_rand(0, $batchSize - 1);

        $_POST['nonce'] = wp_create_nonce(LUG_SLUG . ':nonce.add_user:' . $selectedUserIndex);
        $_POST['i'] = $selectedUserIndex;
        $_POST['user_fname'] = 'Lorem';
        $_POST['user_lname'] = 'Ipsum';
        $_POST['user_email'] = 'loremipsum@foo.sbx';
        $_POST['user_uname'] = 'lorem-ipsum';
        $_POST['user_pwd'] = 'dontstealmyaccountpls';
        $_POST['user_role'] = 'Subscriber';

        try {
            $this->_handleAjax('luser:add_user');
        } catch (WPAjaxDieStopExxception $e) {
            // @todo
        }

        $this->assertFalse(isset($e));

        $response = json_decode($this->_last_response);
        $this->assertInternalType('object', $response);
        $this->assertObjectHasAttribute('success', $response);
        $this->assertTrue($response->success);
    }
}
