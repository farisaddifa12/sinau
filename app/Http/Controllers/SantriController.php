<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SantriController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $santris = santri::latest()->paginate(5);

        //render view with posts
        return view('santris.index', compact('santris'));
    }
     /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('santris.create');
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
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/santris', $image->hashName());

        //create post
        Santri::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('santris.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
  /**
     * edit
     *
     * @param  mixed $santri
     * @return void
     */
    public function edit(Santri $santri)
    {
        return view('santris.edit', compact('santri'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $santri
     * @return void
     */
    public function update(Request $request, Santri $santri)
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/santris', $image->hashName());

            //delete old image
            Storage::delete('public/santris/'.$santri->image);

            //update post with new image
            $santri->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update post without image
            $santri->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('santris.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    /**
     * destroy
     *
     * @param  mixed $santri
     * @return void
     */
    public function destroy(Santri $santri)
    {
        //delete image
        Storage::delete('public/santris/'. $santri->image);

        //delete post
        $santri->delete();

        //redirect to index
        return redirect()->route('santris.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}






