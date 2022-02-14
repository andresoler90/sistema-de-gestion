<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigurableWordClient extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $table = "configurable_words_clients";

    protected $guarded  = [];
}
