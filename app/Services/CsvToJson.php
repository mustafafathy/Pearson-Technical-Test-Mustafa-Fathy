<?php

namespace App\Services;

class CsvToJson
{

    public function convert($path)
    {
        $dataArr = $this->readCsv($path);

        $convertedData = $this->groupStudents($dataArr);

        return json_encode($convertedData, JSON_PRETTY_PRINT);
    }


    protected function readCsv($file)
    {
        $fileHandle = fopen($file, 'r');
        $headers = fgetcsv($fileHandle);

        while ($csvRow = fgetcsv($fileHandle)) {
            $dataArr[] = array_combine($headers, $csvRow);
        }

        fclose($fileHandle);
        return $dataArr;
    }


    protected function groupStudents($dataArr)
    {
        $students = [];

        foreach ($dataArr as $row) {
            $key = $row['Student ID'] . '-' . $row['Subject'];

            if (!isset($students[$key])) {
                $students[$key] = [
                    'student_id' => $row['Student ID'],
                    'name' => $row['Name'],
                    'subject' => $row['Subject'],
                    'scores' => []
                ];
            }

            $students[$key]['scores'][] = [
                'learning_objective' => $row['Learning Objective'],
                'score' => $row['Score']
            ];
        }

        foreach ($students as &$student) {
            $this->sortScores($student);
        }

        return array_values($students);
    }


    protected function sortScores(&$student)
    {
        usort(
            $student['scores'],
            fn($a, $b) => $this->scoreValues($b['score'], $student['subject']) <=> $this->scoreValues($a['score'], $student['subject'])
        );
    }


    protected function scoreValues($score, $subject)
    {
        switch ($subject) {
            case 'English':
                return (int) $score;

            case 'Maths':
                $mathsValues = [
                    'A' => 6,
                    'B' => 5,
                    'C' => 4,
                    'D' => 3,
                    'E' => 2,
                    'F' => 1
                ];
                return $mathsValues[$score] ?? 0;

            case 'Science':
                $scienceValues = [
                    'Excellent' => 5,
                    'Good' => 4,
                    'Average' => 3,
                    'Poor' => 2,
                    'Very Poor' => 1
                ];
                return $scienceValues[$score] ?? 0;

            //  Add a new type of subject and its score mapping
            //  Example:

            //  case 'newSubject':
            //      $newSubjectValues = [
            //          'grade_1' => 5,
            //          'grade_2' => 4,
            //          .........
            //      ];
            //  return $newSubjectValues[$score] ?? 0;   

            default:
                if (is_numeric($score)) {
                    return (int) $score;
                }

                return 0;
        }
    }
}
