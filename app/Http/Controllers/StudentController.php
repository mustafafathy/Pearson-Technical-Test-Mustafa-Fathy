<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvRequest;
use App\Services\CsvToJson;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function convertCsvToJson(CsvRequest $request)
    {
        $file = $request->file('csv_file');
        $relativePath = Storage::putFile('tmp_csv_file', $file);
        $absolutePath = Storage::path($relativePath);

        $converter = new CsvToJson();
        $jsonData = $converter->convert($absolutePath);

        Storage::delete($relativePath);

        return response()->json(json_decode($jsonData), 200);
    }
}
