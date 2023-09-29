<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    //--index
    public function index()
    {
        $students = Student::get();
        return response()->json($students);
    }

    //---store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'course' => 'required',
            'class' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        } else {

            $student = Student::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'course'=>$request->course,
                'class'=>$request->class,
            ]);

            if($student){
                return response()->json([
                    'status'=>200,
                    'message'=>'Student Added Successfully!',
                ], 200);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Something Went Wrong. Please Try Again!',
                ], 500);
            }
        }
    }

    //---edit
    public function edit($id)
    {
        $student  = Student::where('id', $id)->first();
        if($student){
            return response()->json([
                'status'=>200,
                'student'=>$student
            ], 200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'Student Not Found'
            ], 404);
        }
    }

    //---update
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'course' => 'required',
            'class' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ], 422);
        }
        else{
            $student  = Student::where('id', $id)->first();
            if($student){
                $student->name = $request->name;
                $student->email = $request->email;
                $student->course = $request->course;
                $student->class = $request->class;
                $student->update();

                return response()->json([
                    'status'=>200,
                    'message'=>'Student Update Successfully',
                ], 200);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Student Not Found!'
                ], 404);
            }
        }
        
    }

    //---delete
    public function delete($id)
    {
        $student = Student::findOrFail($id);
        if($student){
            $student->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Student Delete SuccessFully!',
            ], 200);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Student Not Found!',
            ], 404);
        }
    }
}
