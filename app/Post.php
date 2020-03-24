<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $casts = [
      'publish_at' => 'datetime',
      'publish_until' => 'datetime'
    ];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function tags() {
      return $this->belongsToMany(Tag::class);
    }
}
