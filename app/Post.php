<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
  use Searchable;

    protected $casts = [
      'publish_at' => 'datetime',
      'publish_until' => 'datetime'
    ];

    protected $fillable = [
      'is_published'
    ];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function tags() {
      return $this->belongsToMany(Tag::class);
    }
}
