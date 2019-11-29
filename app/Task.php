<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable=
        [
        'keyword_id',
        'project_id',
        'user_id',
        'task_API_id',
        'string_time'
    ];
}
