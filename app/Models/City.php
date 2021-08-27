<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $primaryKey = 'id_city';

    protected $fillable = [
        'id_state',
        'name'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'id_state', 'id_state');
    }

    public function scopeSearchByName($query, $text)
    {
        $query->where('name', 'LIKE', "%".$text."%");
    }
}
