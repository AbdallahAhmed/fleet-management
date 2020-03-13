<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "bookings";
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function source()
    {
        return $this->hasOne(City::class, 'id','source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function destination()
    {
        return $this->hasOne(City::class, 'id','destination_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
