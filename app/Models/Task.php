<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','task_title','priority','tags','space','red_flag','zone','due_date','status',];

    public function taskpart()
    {
        return $this->hasMany(TaskParticipate::class,'task_id','id');
    }
}
