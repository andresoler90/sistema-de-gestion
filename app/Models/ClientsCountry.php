<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientsCountry extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'countries_id');
    }
}
