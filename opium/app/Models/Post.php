<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    //
    protected $fillable = [
        'category_id', 'user_id', 'title', 'description', 'photo'
    ];

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Category::class, 'id', 'id');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function setPhotoAttribute($value){
        $attribute_name = "image";
        $disk = "public";
        $destination_path = "/images";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
