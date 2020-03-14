<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
     * Scope opened
     * @param $query
     * @return mixed
     */
    public function scopeOpened($query){
        return $query->whereHas('trip', function ($q){
          return $q->where('date_to_book', '>=', Carbon::now()->format('Y-m-d'));
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function source()
    {
        return $this->hasOne(City::class, 'id', 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function destination()
    {
        return $this->hasOne(City::class, 'id', 'destination_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function trip(){
        return $this->hasOne(Trip::class, 'id', 'trip_id');
    }


}
