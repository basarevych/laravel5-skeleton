<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetProfileForm()
    {
        $data = $this->get('/profile-form');
        $this->assertEquals(401, $data->response->getStatusCode(), "Authentication is required");

        $user = factory(App\User::class)->create();
        $this->actingAs($user)
             ->visit('/profile-form')
             ->see(trans('profile.name_label'))
             ->see(trans('profile.email_label'))
             ->see(trans('profile.password1_label'))
             ->see(trans('profile.password2_label'));
    }

    public function testPostInvalidProfileForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $user = factory(App\User::class)->create();
        $data = $this->actingAs($user)->get('/profile-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'    => $token,
        ];

        $data = $this->actingAs($user)->post('/profile-form', $params, $headers);
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
            'password'  => 'password without confirmation',
        ];

        $data = $this->actingAs($user)->post('/profile-form', $params, $headers);
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
        $this->assertEquals(
            trans('validation.required_with', [ 'attribute' => 'password confirmation', 'values' => 'password' ]),
            $result['password_confirmation'][0],
            "Password confirmation error should be set"
        );
    }

    public function testPostValidProfileForm()
    {
        $headers = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $user = factory(App\User::class)->create();
        $data = $this->actingAs($user)->get('/profile-form', [], $headers);
        $token = $this->getToken($data->response->getContent());

        $params = [
            '_token'                => $token,
            'name'                  => 'new name',
            'email'                 => 'new.email@example.com',
            'password'              => 'new password',
            'password_confirmation' => 'new password',
        ];

        $data = $this->actingAs($user)->post('/profile-form', $params, $headers);
        $this->assertEquals(200, $data->response->status(), "POST should result in success");
        $this->assertTrue(
            strpos($data->response->getContent(), "window.location.reload()") != false,
            "Page should be reloaded"
        );

        $this->seeInDatabase('users', [ 'name' => 'new name', 'email' => 'new.email@example.com' ]);
    }
}
