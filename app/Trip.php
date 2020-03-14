<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Collection;

class Trip extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "trips";

    protected $fillable = ['source_id', 'destination_id', 'date_to_book'];

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
        return $query->where('date_to_book', '>=', Carbon::now()->format('Y-m-d'))->get();
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
        return $this->belongsToMany(User::class, 'bookings')->distinct();
    }

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
     * Trip Cities
     * @return Collection
     */
    public function getCitiesAttribute(){
        $source_id = $this->source_id;
        $destination_id = $this->destination_id;
        return City::whereBetween('id', array($source_id, $destination_id))->orderBy('id', 'ASC')->get();
    }
}
