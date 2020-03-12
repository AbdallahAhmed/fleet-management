<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Trip extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "trips";

    protected $fillable = ['city_from_id', 'city_to_id', 'booking_date'];

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
    public function scopeOpened($query)
    {
        return $query->where('booking_date', '>=', Carbon::now()->format('Y-m-d'))->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bookings');
    }
}
