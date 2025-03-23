<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Task extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'priority',
        'status',
        'due_date',
        'share_token',
        'share_token_expiration',
    ];

    // Relacja: zadanie należy do użytkownika
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function revisions()
{
    return $this->hasMany(\App\Models\TaskRevision::class)->orderBy('changed_at', 'desc');
}
}