<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("students.index");
    }
    
    public function getdata(Request $request)
    {
        $columns        = [ 'id', 'name', 'email', 'created_at', 'updated_at'];
        $length         = $request->input('length');
        $column         = $request->input('column', 0);
        $dir            = $request->input('dir', 'asc');
        $searchValue    = $request->input('search') ['value'];// Include search value

        if (!is_numeric($column) || $column < 0 || $column >= count($columns)) {
            $column = 0; // Default to the first column
        }

        $query      = Student::select($columns)
            ->when($searchValue, function ($query, $searchValue) {
                return $query->where('name', 'like', "%". $searchValue ."%");
            })
            ->orderBy($columns[$column], $dir);

        $totalData  = $query->count();
        $students   = $query->offset($request->input('start'))->limit($length)->get();

        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'id'            => $student->id,
                'name'          => $student->name,
                'email'         => $student->email,
                'created_at'    => $student->created_at->format('Y-m-d H:i:s'),
                'updated_at'    => $student->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        $json_data = [
            "draw"              => intval($request->input('draw')),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalData),
            "data"              => $data
        ];
        return response()->json($json_data);
    }
}
