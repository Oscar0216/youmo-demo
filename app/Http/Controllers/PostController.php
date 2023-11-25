<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = POST::where('active', true);

        if ($request->ajax()) {

            if($request->has('create_sort')){
                $sort = $request->input('create_sort');
                if(!empty($sort)){
                    $query->orderBy('created_at', $sort);
                }
            }

            if($request->has('search')){
                $search = $request->input('search');
                if(!empty($search)){
                    $query->where(function ($query) use ($search) {
                        $query->where('title', 'like', '%' . $search .'%');
                        $query->orWhere('description', 'like', '%' . $search .'%');
                    });
                }
            }

            $posts = $query->get();
            $html = view('post.components.filter', [
                'posts' => $posts,
            ])->render();

            $output = [
                'html' => $html
            ];

            return $output;
        }
        
        $posts = $query->get();

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {

        try {
            $inputs = $request->validated();

            if (isset($inputs['image'])){
                unset($inputs["image"]);

                $name = time().'_'.$request->file('image')->getClientOriginalName();
                $path = '/storage/'. $request->file('image')->storeAs(
                    'post', 
                    $name,
                    'public'
                );
        
                $inputs["image"] = $path;
            }
            $post = Post::create($inputs);
        } catch (\Throwable $th) {
            \Log::emergency("File:" . $th->getFile(). ", Line:" . $th->getLine(). ", Message:" . $th->getMessage());
            return redirect()->route('posts.index')->withErrors("Create post failed!");
        }

        return redirect('posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        try {
            $inputs = $request->validated();

            if (isset($inputs['image'])){
                unset($inputs["image"]);

                Storage::disk('public')->delete(
                    str_replace(
                        '/storage/',
                        '',
                        $post->image
                    )
                );
    
                $name = time().'_'.$request->file('image')->getClientOriginalName();
                $path = '/storage/'. $request->file('image')->storeAs(
                    'post', 
                    $name,
                    'public'
                );
        
                $inputs["image"] = $path;
            }
            $post->update($inputs);
        } catch (\Throwable $th) {
            \Log::emergency("File:" . $th->getFile(). ", Line:" . $th->getLine(). ", Message:" . $th->getMessage());
            return redirect()->route('posts.index')->withErrors("Update post failed!");
        }

        return redirect('posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Storage::disk('public')->delete(
            str_replace(
                '/storage/',
                '',
                $post->image
            )
        );

        $post->delete();
        $output = [
            'success' => true,
            'msg' => "Post deleted"
        ];
        
        return $output;
    }
}
