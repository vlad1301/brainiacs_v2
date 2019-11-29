<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable=[
        'task_id',
        'keyword_id',
        'project_id',
        'user_id',
        'result_task_id',
        'result_post_id',
        'result_se_id',
        'result_loc_id',
        'key_id',
        'post_key',
        'result_position',
        'result_datetime',
        'result_url',
       ];
}
