<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'responsible_id',
        'due_date',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'responsible_id');
    }
}
