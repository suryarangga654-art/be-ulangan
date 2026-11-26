<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function getImageUrlAttribute(): ?string
    {
        return $this->image ? url('api/media/'.$this->image) : null;
    }
}
