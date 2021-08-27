<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'id_user',
        'id_country',
        'id_state',
        'id_city',
        'place_description',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'id_country', 'id_country');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'id_state', 'id_state');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city', 'id_city');
    }

    public function scopeSearchByTitleOrDescription($query, $text)
    {
        $query->where('title', 'LIKE', '%'.$text.'%')
            ->orWhere('description', 'LIKE', '%'.$text.'%');
    }

    public function scopeSearchByPlace($query, $placeDescription)
    {

        $query->join('cities', 'jobs.id_city', '=', 'cities.id_city')
            ->join('states', 'cities.id_state', '=', 'states.id_state')
            ->join('countries', 'states.id_country', '=', 'countries.id_country');

        $query->where('cities.name', 'LIKE', '%'.$placeDescription.'%')
            ->orWhere('states.name', 'LIKE', '%'.$placeDescription.'%')
            ->orWhere('countries.name', 'LIKE', '%'.$placeDescription.'%');

        // $query->where('place_description', 'LIKE', '%'.$placeDescription.'%');
    }
}
