<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    /**
     * Token types
     *
     * @const TYPE_PASSWORD_RESET
     * @const TYPE_REGISTRATION
     */
    const TYPE_PASSWORD_RESET = 'password-reset';
    const TYPE_REGISTRATION = 'registration';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ 'user_id', 'token' ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Generate unique token
     *
     * @return string
     */
    public static function generateToken()
    {
        $randomData = openssl_random_pseudo_bytes(1024);
        if ($randomData === false)
            throw new \Exception('Could not generate random string');

        return substr(hash('sha512', $randomData), 0, 32);
    }
}
