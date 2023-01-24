<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $students = student::latest()->paginate(5);

        //render view with posts
        return view('students.index', compact('students'));
    }
     /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * store
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'name'     => 'required|min:5',
            'email'   => 'required|min:10',
            'phone'   => 'required|min:11',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/students', $image->hashName());

        //create post
        Student::create([
            'name'     => $request->name,
            'email'   => $request->email,
            'phone'     => $request->phone,
            'image'     => $image->hashName()
        ]);

        //redirect to index
        return redirect()->route('students.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
  /**
     * edit
     *
     * @param  mixed $student
     * @return void
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $student
     * @return void
     */
    public function update(Request $request, Student $student)
    {
        //validate form
        $this->validate($request, [
            'name'     => 'required|min:5',
            'email'   => 'required|min:10',
            'phone'   => 'required|min:11',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/students', $image->hashName());

            //delete old image
            Storage::delete('public/students/'.$student->image);

            //update post with new image
            $student->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'image'     => $image->hashName()
            ]);

        } else {

            //update post without image
            $student->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
            ]);
        }

        //redirect to index
        return redirect()->route('students.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    /**
     * destroy
     *
     * @param  mixed $student
     * @return void
     */
    public function destroy(Studend $student)
    {
        //delete image
        Storage::delete('public/students/'. $student->image);

        //delete post
        $student->delete();

        //redirect to index
        return redirect()->route('students.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}






