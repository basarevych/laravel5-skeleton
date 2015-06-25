<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PasswordControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Config::set('recaptcha.site_key', null);
        Config::set('recaptcha.secret', null);
    }

    public function testGetResetRequestForm()
    {
        $this->visit('auth/reset-request-form')
             ->see(trans('password.email_label'));
    }

    public function testPostInvalidResetRequestForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $user = factory(App\User::class)->create([ 'email' => 'tester@example.com' ]);
        $data = $this->get('auth/reset-request-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'    => $token,
        ];

        $data = $this->post('auth/reset-request-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'eMail' ]),
            $result['email'][0],
            "Email error should be set"
        );

        $params = [
            '_token'    => $token,
            'email'     => 'invalid email address',
        ];

        $data = $this->post('auth/reset-request-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.email', [ 'attribute' => 'eMail' ]),
            $result['email'][0],
            "Email error should be set"
        );

        $params = [
            '_token'    => $token,
            'email'     => 'wrong.email@example.com',
        ];

        $data = $this->post('auth/reset-request-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.exists', [ 'attribute' => 'eMail' ]),
            $result['email'][0],
            "Email error should be set"
        );
    }

    public function testPostValidResetRequestForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $user = factory(App\User::class)->create([ 'email' => 'admin@example.com' ]);
        $data = $this->get('auth/reset-request-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        Mail::shouldReceive('send')
              ->once();

        $params = [
            '_token'    => $token,
            'email'     => 'admin@example.com',
        ];

        $data = $this->post('auth/reset-request-form', $params, $headers);
        $this->assertEquals(200, $data->response->status(), "POST should result in success");
        $this->assertTrue(
            strpos($data->response->getContent(), "bsAlert") != false,
            "Alert should be displayed"
        );
    }

    public function testGetInvalidResetConfirm()
    {
        $data = $this->get('auth/reset-confirm/foobar');
        $this->assertTrue(
            strpos($data->response->getContent(), "bsAlert") != false,
            "Alert should be displayed"
        );
    }

    public function testGetValidResetConfirm()
    {
        $reset = factory(App\PasswordReset::class)
                    ->make();
        $user = factory(App\User::class)
                    ->create();
        $user->passwordResets()->save($reset);

        $data = $this->get('auth/reset-confirm/' . $reset->token);
        $this->assertTrue(
            strpos($data->response->getContent(), "openModalForm") != false,
            "Form should be displayed"
        );
    }

    public function testGetResetConfirmForm()
    {
        $reset = factory(App\PasswordReset::class)
                    ->make();
        $user = factory(App\User::class)
                    ->create();
        $user->passwordResets()->save($reset);

        $this->visit('auth/reset-confirm-form/' . $reset->token)
             ->see(trans('password.password1_label'))
             ->see(trans('password.password2_label'));
    }

    public function testPostInvalidResetConfirmForm()
    {
        $reset = factory(App\PasswordReset::class)
                    ->make();
        $user = factory(App\User::class)
                    ->create();
        $user->passwordResets()->save($reset);

        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('auth/reset-confirm-form/' . $reset->token, [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'        => $token,
            'reset_token'   => $reset->token,
        ];

        $data = $this->post('auth/reset-confirm-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'password' ]),
            $result['password'][0],
            "Password error should be set"
        );
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'password confirmation' ]),
            $result['password_confirmation'][0],
            "Password confirmation error should be set"
        );

        $params = [
            '_token'                => $token,
            'reset_token'           => $reset->token,
            'password'              => 'string 1',
            'password_confirmation' => 'string 2',
        ];

        $data = $this->post('auth/reset-confirm-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.confirmed', [ 'attribute' => 'password' ]),
            $result['password'][0],
            "Password error should be set"
        );
    }

    public function testPostValidResetConfirmForm()
    {
        $reset = factory(App\PasswordReset::class)
                    ->make();
        $user = factory(App\User::class)
                    ->create([ 'email' => 'admin@example.com' ]);
        $user->passwordResets()->save($reset);

        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('auth/reset-confirm-form/' . $reset->token, [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'                => $token,
            'reset_token'           => $reset->token,
            'password'              => 'string 1',
            'password_confirmation' => 'string 1',
        ];

        $data = $this->post('auth/reset-confirm-form', $params, $headers);
        $this->assertEquals(200, $data->response->status(), "POST should result in success");
        $this->assertTrue(
            strpos($data->response->getContent(), "window.location = '" . url('/') . "'") != false,
            "Page should be reloaded"
        );
        $this->assertTrue(
            Auth::once([ 'email' => 'admin@example.com', 'password' => 'string 1' ]),
            "Password should be changed"
        );
    }
}
