<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'label', 'conclusion_at'
    ];

    public function tasks()
    {
        return $this->hasMany(related:TodoTask::class);
    }
}
