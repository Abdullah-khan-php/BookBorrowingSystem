<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    protected $fillable = ['user_id', 'borrowable_id', 'borrowable_type', 'borrowed_at', 'returned_at'];

    public function borrowable()
    {
        return $this->morphTo();
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}
