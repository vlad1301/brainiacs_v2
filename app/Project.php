<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=[
        'user_id',
        'name',
        'domain',
        'engine',
        'engine_id',
        'language',
        'location',
        'location_id',
        'cronjob',
       ];
}
