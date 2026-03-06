<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'marks',
        'total_marks',
        'percentage',
        'grade',
        'semester',
        'academic_year',
        'remarks',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Accessors
    public function getGradeColorAttribute()
    {
        return match ($this->grade) {
            'A+' => 'success',
            'A' => 'primary',
            'B' => 'info',
            'C' => 'warning',
            default => 'danger',
        };
    }

    public function getPassFailAttribute()
    {
        return $this->percentage >= 60 ? 'Pass' : 'Fail';
    }
}