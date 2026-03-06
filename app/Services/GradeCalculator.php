<?php

namespace App\Services;

class GradeCalculator
{
    public static function calculate($marks, $totalMarks = 100)
    {
        $percentage = ($marks / $totalMarks) * 100;
        $grade = self::getGrade($percentage);

        return [
            'percentage' => round($percentage, 2),
            'grade' => $grade,
        ];
    }

    private static function getGrade($percentage)
    {
        if ($percentage >= 90) {
            return 'A+';
        }
        elseif ($percentage >= 80) {
            return 'A';
        }
        elseif ($percentage >= 70) {
            return 'B';
        }
        elseif ($percentage >= 60) {
            return 'C';
        }
        else {
            return 'F';
        }
    }

    public static function getGPA($grade)
    {
        return match ($grade) {
                'A+' => 4.0,
                'A' => 3.7,
                'B' => 3.0,
                'C' => 2.0,
                'F' => 0.0,
                default => 0.0,
            };
    }
}