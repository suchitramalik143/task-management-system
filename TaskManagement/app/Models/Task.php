<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task_management_system';
    public $timestamps = true;
    protected $fillable = ['title', 'description', 'due_date', 'priority', 'status', 'assigned_to', 'created_by'];


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to','id');
    }
}
