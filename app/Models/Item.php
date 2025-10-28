<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;


class Item extends Model
{
    use HasFactory, HasEagerLimit;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'parent_id', 'id')->where('type', '=', 'item');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
