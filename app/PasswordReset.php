<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BaseModel;

class PasswordReset extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

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
