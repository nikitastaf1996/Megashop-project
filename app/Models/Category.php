<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
