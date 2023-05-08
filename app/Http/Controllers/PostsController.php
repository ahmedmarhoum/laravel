<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;

class PostsController extends Controller
{
    

    public function index()
    {
       return view('blog.index')->with('posts', Post::get());
    }

   
    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=> 'required|mimes:jpg,png,jped|max:5048',
        ]);
        $slug = Str::slug($request->title,'-');
        $imageName = uniqid(). '-' . $slug .'.'. $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Post::create([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'slug'=>$slug,
            'image_path'=>$imageName,
            'user_id'=>auth()->user()->id,
        ]);
        return redirect('/blog');
      
    }


    public function show($slug)
    {
        return view('blog.show')->with('post', Post::where('slug', $slug)->first());
    }


    public function edit($slug)
    {
        return view('blog.edit')->with('post', Post::where('slug', $slug)->first());
    }

  
    public function update(Request $request, $slug)
    {
       

        Post::where('slug',$slug)->update([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'slug'=>$slug,
            'user_id'=>auth()->user()->id,
        ]);
        return redirect('/blog/'.$slug)->with('message','the post is updated succefly');
    }

   
    public function destroy($slug)
    {
        Post::where('slug', $slug) ->delete([

        ]);
        return redirect('/blog')->with('message', 'the post deleted succefly!');
    }
}
