<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $primaryKey = 'id_state';

    protected $fillable = [
        'id_country',
        'name'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'id_country', 'id_country');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'id_state');
    }

    public function scopeSearchByName($query, $text)
    {
        $query->where('name', 'LIKE', "%".$text."%");
    }
}
