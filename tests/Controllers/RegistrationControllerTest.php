<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Token;

class RegistrationControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Config::set('recaptcha.site_key', null);
        Config::set('recaptcha.secret', null);
        Config::set('auth.registration.enable', true);
        Config::set('auth.registration.confirm', true);
    }

    public function testGetInvalidRegistration()
    {
        $data = $this->get('auth/registration/foobar');
        $this->assertTrue(
            strpos($data->response->getContent(), "bsAlert") != false,
            "Alert should be displayed"
        );
    }

    public function testGetValidRegistration()
    {
        $reset = factory(App\Token::class, Token::TYPE_REGISTRATION)->make();
        $user = factory(App\User::class)->create();
        $user->tokens()->save($reset);

        $user->active = false;
        $user->save();

        $data = $this->get('auth/registration/' . $reset->token);
        $this->assertTrue(
            strpos($data->response->getContent(), "openModalForm") != false,
            "Form should be displayed"
        );
        $this->assertTrue(
            (bool)App\User::find(1)->first()->active,
            "User account should be activated"
        );
        $this->assertEquals(
            0,
            count(App\Token::all()),
            "No tokens should remain"
        );
    }

    public function testGetRegistrationForm()
    {
        $this->visit('auth/registration-form')
             ->see(trans('registration.name_label'))
             ->see(trans('registration.email_label'))
             ->see(trans('registration.password1_label'))
             ->see(trans('registration.password2_label'));
    }

    public function testPostInvalidRegisttrationForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('auth/registration-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'        => $token,
        ];

        $data = $this->post('auth/registration-form', $params, $headers);
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
        $this->assertEquals(
            trans('validation.filled', [ 'attribute' => 'password confirmation' ]),
            $result['password_confirmation'][0],
            "Password confirmation error should be set"
        );

        $params = [
            '_token'                => $token,
            'email'                 => 'invalid email',
            'password'              => 'string 1',
            'password_confirmation' => 'string 2',
        ];

        $data = $this->post('auth/registration-form', $params, $headers);
        $result = $data->response->getData(true);
        $this->assertEquals(422, $data->response->status(), "POST should result in 422");
        $this->assertEquals(
            trans('validation.email', [ 'attribute' => 'eMail' ]),
            $result['email'][0],
            "Email error should be set"
        );
        $this->assertEquals(
            trans('validation.confirmed', [ 'attribute' => 'password' ]),
            $result['password'][0],
            "Password error should be set"
        );
    }

    public function testPostValidRegistrationForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $data = $this->get('auth/registration-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'                => $token,
            'email'                 => 'new@example.com',
            'password'              => 'string 1',
            'password_confirmation' => 'string 1',
        ];

        Mail::shouldReceive('send')
            ->once();

        $data = $this->post('auth/registration-form', $params, $headers);
        $user = App\User::all()->first();
        $token = App\Token::all()->first();

        $this->assertEquals(200, $data->response->status(), "POST should result in success");
        $this->assertTrue(
            strpos($data->response->getContent(), "bsAlert") != false,
            "Alert should be displayed"
        );
        $this->assertTrue(
            Auth::once([ 'email' => 'new@example.com', 'password' => 'string 1' ]),
            "User should be created"
        );
        $this->assertFalse(
            (bool)$user->active,
            "User account should be non-active"
        );
        $this->assertEquals(
            Token::TYPE_REGISTRATION,
            $token->type,
            "Registration token should exist"
        );
        $this->assertEquals(
            $user->id,
            $token->user()->first()->id,
            "User token should exist"
        );
    }
}
