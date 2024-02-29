<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /** add teacher page */
    public function teacherAdd()
    {
        $users = User::where('role_name','Teachers')->get();
        return view('teacher.add-teacher',compact('users'));
    }

    /** teacher list */
    public function teacherList()
    {
        $listTeacher = Teacher::join('users', 'teachers.user_id','users.user_id')
                    ->select('users.date_of_birth','users.join_date','users.phone_number','teachers.*')->get();
        return view('teacher.list-teachers',compact('listTeacher'));
    }

    /** teacher Grid */
    public function teacherGrid()
    {
        $teacherGrid = Teacher::all();
        return view('teacher.teachers-grid',compact('teacherGrid'));
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string',
            'gender'        => 'required|string',
            'experience'    => 'required|string',
            'qualification' => 'required|string',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'zip_code'      => 'required|string',
            'country'       => 'required|string',
        ]);

        try {

            $saveRecord = new Teacher;
            $saveRecord->full_name     = $request->full_name;
            $saveRecord->user_id       = $request->teacher_id;
            $saveRecord->gender        = $request->gender;
            $saveRecord->experience    = $request->experience;
            $saveRecord->qualification = $request->qualification;
            $saveRecord->address       = $request->address;
            $saveRecord->city          = $request->city;
            $saveRecord->state         = $request->state;
            $saveRecord->zip_code      = $request->zip_code;
            $saveRecord->country       = $request->country;
            $saveRecord->save();
   
            Toastr::success('Has been add successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            Log::info($e);
            Toastr::error('fail, Add new record  :)','Error');
            return redirect()->back();
        }
    }

    /** edit record */
    public function editRecord($user_id)
    {
        $teacher = Teacher::join('users', 'teachers.user_id','users.user_id')
                    ->select('users.date_of_birth','users.join_date','users.phone_number','teachers.*')
                    ->where('users.user_id', $user_id)->first();
        return view('teacher.edit-teacher',compact('teacher'));
    }

    /** update record teacher */
    public function updateRecordTeacher(Request $request)
    {
        try {

            $updateRecord = [
                'full_name'     => $request->full_name,
                'gender'        => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'mobile'        => $request->mobile,
                'joining_date'  => $request->joining_date,
                'qualification' => $request->qualification,
                'experience'    => $request->experience,
                'username'      => $request->username,
                'address'       => $request->address,
                'city'          => $request->city,
                'state'         => $request->state,
                'zip_code'      => $request->zip_code,
                'country'      => $request->country,
            ];
            Teacher::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            return redirect()->back();
           
        } catch(\Exception $e) {
            Log::info($e);
            Toastr::error('fail, update record  :)','Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function teacherDelete(Request $request)
    {
        try {

            Teacher::destroy($request->id);
            Toastr::success('Deleted record successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            Log::info($e);
            Toastr::error('Deleted record fail :)','Error');
            return redirect()->back();
        }
    }
}
