<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TaskRevision extends Model
{
    use HasFactory, HasUuids;
    
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'user_id',
        'changes',
        'changed_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'changed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
