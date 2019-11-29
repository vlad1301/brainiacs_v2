<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    //
    protected $fillable=[
        'project_id',
        'user_id',
        'value'
    ];
}
