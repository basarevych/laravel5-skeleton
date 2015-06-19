<?php

namespace App;

use Config;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;

/**
 * Always store DateTime in database as UTC
 */
abstract class BaseModel extends Model
{
    /**
     * Get effective timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return Config::get('app.timezone', 'UTC');
    }

    /**
     * Convert a DateTime to a storable string.
     *
     * @param  \DateTime|int  $value
     * @return string
     */
    public function fromDateTime($value)
    {
        if ($value instanceof DateTime) {
            $value = clone $value;
            $value->setTimezone(new DateTimeZone('UTC'));
        }

        return parent::fromDateTime($value);
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        $default = date_default_timezone_get();
        date_default_timezone_set('UTC');

        $result = parent::asDateTime($value);
        $result->setTimezone(new DateTimeZone($this->getTimezone()));

        date_default_timezone_set($default);

        return $result;
    }
}
