<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTable extends Model
{
    use HasFactory;

    protected $table = 'test_table';
    protected $fillable = [
        'status',
        'priority',
        'title',
        'description',
        'completedAt',
        'parent_id',
        'user_id',
    ];

    public function subtasks()
    {
        return $this->hasMany(TestTable::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(TestTable::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
