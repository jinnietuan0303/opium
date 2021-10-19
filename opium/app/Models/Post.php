<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    //
    protected $fillable = [
        'category_id', 'user_id', 'title', 'description', 'photo'
    ];
}
