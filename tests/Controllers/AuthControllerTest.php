<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        $_SERVER['X_Requested_With'] = 'XMLHttpRequest';

        parent::setUp();
    }

    public function testGetLoginForm()
    {
        $this->visit('/auth/login-form')
             ->see('eMail:')
             ->see('Password:');
    }

    public function testPostInvalidLoginForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('/auth/login-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'    => $token,
        ];

        $data = $this->post('/auth/login-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'eMail' ]),
            $result['email'][0],
            "Email error should be set"
        );
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'password' ]),
            $result['password'][0],
            "Password error should be set"
        );

        $params = [
            '_token'    => $token,
            'email'     => 'admin@example.com',
            'password'  => 'passwd',
        ];

        $data = $this->post('/auth/login-form', $params, $headers);
        $this->assertEquals(302, $data->response->status(), "POST should result in redirect");
        $this->assertEquals(
            trans('auth.invalid_credentials'),
            session('message'),
            "Invalid credentials should be rejected"
        );
    }

    public function testPostValidLoginForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('/auth/login-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'    => $token,
            'email'     => 'admin@example.com',
            'password'  => 'passwd',
        ];

        $user = factory(App\User::class)->create([
            'name'      => 'Admin',
            'email'     => $params['email'],
            'password'  => bcrypt($params['password']),
        ]);

        $data = $this->post('/auth/login-form', $params, $headers);
        $this->assertEquals(200, $data->response->status(), "POST should result in success");
    }
}
