<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    public $timestamps = false;

    protected $primaryKey = 'id_country';

    protected $fillable = [
        'name'
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'id_country');
    }

    public function scopeSearchByName($query, $text)
    {
        $query->where('name', 'LIKE', "%".$text."%");
    }
}
