<?php

namespace App;

use App\Http\Controllers\ShowsController;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['shows_count', 'shows_ids'];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'bookings');
    }

    /**
     * generate API token
     * @return string
     */
    static function newApiToken()
    {
        return Str::random(60);
    }

    public function getShowsCountAttribute(){
        return DB::table('users_shows')->where('user_id', '=', $this->id)->count();
    }

    public function getShowsIdsAttribute(){
        return DB::table('users_shows')->where('user_id', '=', $this->id)->pluck('show_id')->toArray();
    }


    /**
     * Set password attribute
     * @param $password
     */
    function setPasswordAttribute($password)
    {
        $this->attributes["password"] = Hash::make($password);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => $this->role];
    }
}
