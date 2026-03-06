<?php

namespace App\Models;

use App\Services\GradeCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'roll_number',
        'date_of_birth',
        'guardian_name',
        'guardian_phone',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Calculate overall GPA (0.0 – 4.0 scale)
    public function getGpaAttribute()
    {
        $results = $this->results;

        if ($results->isEmpty()) {
            return 0.0;
        }

        $totalGpa = 0.0;
        foreach ($results as $r) {
            $totalGpa += GradeCalculator::getGPA($r->grade);
        }
        return round($totalGpa / $results->count(), 2);
    }
}