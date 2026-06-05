<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    protected $fillable = [
        'user_id',
        'amount_of_reviews',
        'average_grade',
        'favourite_genre',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}