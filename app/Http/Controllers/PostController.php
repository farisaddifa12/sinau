<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = post::latest()->paginate(5);

        //render view with posts
        return view('posts.index', compact('posts'));
    }
     /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('posts.create');
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
            'name' => 'required|min:5',
            'email' => 'required|min:10',
            'phone' => 'required|min:10',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);

        //upload photo$photo
        $photo = $request->file('photo');
        $photo->storeAs('public/posts', $photo->hashName());

        //create post
        Post::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $photo->hashName()
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
  /**
     * edit
     *
     * @param  mixed $post
     * @return void
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Post $post)
    {
        //validate form
        $this->validate($request, [
            'name' => 'required|min:5',
            'email' => 'required|min:10',
            'phone' => 'required|min:10',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);

        //check if photo$photo is uploaded
        if ($request->hasFile('photo')) {

            //upload new photo
            $photo = $request->file('photo');
            $photo->storeAs('public/posts', $photo->hashName());

            //delete old photo$photo
            Storage::delete('public/posts/'.$post->photo);

            //update post with new photo
            $post->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'photo' => $photo->hashName()
            ]);

        } else {

            //update post without photo$photo
            $post->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Post $post)
    {
        //delete photo$photo
        Storage::delete('public/posts/'. $post->photo);

        //delete post
        $post->delete();

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}






