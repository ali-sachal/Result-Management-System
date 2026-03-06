<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'class_id',
        'teacher_id',
        'total_marks',
        'description',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class , 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}