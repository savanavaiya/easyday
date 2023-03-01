<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskParticipate extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','task_id','task_participate','status'];

    public function taskpartname()
    {
        return $this->hasMany(Participate::class,'id','task_participate');
    }
}
