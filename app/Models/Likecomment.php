<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likecomment extends Model
{
    use HasFactory;

    protected $fillable = ['likeruser','comment_id','status'];
}
