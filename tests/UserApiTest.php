<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\MailQueue;

class UserApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegistration()
    {
        // registration
        $email  = 'u' . rand(10,999) . '@mail.com';
        $passw  = rand(8, 99999);
        $user   = User::createUser([ 'email' => $email, 'password' => $passw ]);
        $this->assertTrue(isset($user->activate_code));

        $acode  = $user->activate_code;
        $queue  = MailQueue::where('email', $email)->first();
        $this->assertTrue(isset($queue->email));
        $this->assertEquals($email, $queue->email);
        $this->assertEquals($acode, $queue->activate_code);

        // get
        $user = User::getByEmail($email);
        $this->assertFalse($user->is_active == 1);

        // activate 
        $user = User::activate($acode);
        $this->assertEquals($user->email, $email);
        $this->assertEquals($user->is_active, 1);
        $this->assertEquals($user->password, User::hash($passw));

        $user = User::activate('wrong code');
        $this->assertFalse(isset($user->email));

        // get
        $user = User::getByEmail($email);
        $this->assertTrue(isset($user->email));

        // auth
        $user = User::auth($email, $passw);
        $this->assertEquals($user->email, $email);
        $this->assertEquals($user->is_active, 1);
        $this->assertEquals($user->password, User::hash($passw));

        $apiToken = $user->api_token;
        $tokenExpire = $user->token_expire_at;

        // get
        $user = User::getByEmail($email);
        $this->assertEquals($apiToken, $user->api_token);
        $this->assertEquals($tokenExpire, $user->token_expire_at);

        // auth
        $user = User::auth($email, 'wrong passw');
        $this->assertFalse(isset($user));

        $user = User::auth($email, $passw);
        $this->assertTrue($apiToken != $user->api_token);
    }
}
