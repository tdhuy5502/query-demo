<?php

namespace App\Http\Controllers;

use App\Models\Catogory;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use illuminate\Support\Str;

class PostController extends Controller
{
    //
    public function index()
    {
        $posts = Post::all();

        $status = 'published';
        $postsPublished = Post::where('status',$status)->get();

        $top10Posts = Post::orderBy('created_at')->limit(10)->get();

        $userId = User::whereNotNull('deleted_at')->pluck('id')->first();

        Post::create([
            'name' => 'Name',
            'user_id' => $userId
        ]);

        Comment::where('post_id',10)->delete();

        Post::with('comments')->get();
    }

    public function user_query()
    {
        User::create([
            'name' => 'Name',
            'email' => 'Email',
            'password' => bcrypt(Str::random(10))
        ]);

        User::where('last_login', '<' , Carbon::now()->subYear())->delete();

        User::where('id' ,'=' ,'5')->update([
            'email' => 'Email',
        ]);

        User::has('posts', '>=' ,5)->get();
    }

    public function orders_query()
    {
        Order::where('amount', '>' , 1000)->update([
            'status' => 'completed'
        ]);

        $categories = Catogory::withCount('products')->get();

        $posts = Post::orderBy('created_at','asc')->take(5)->get();

        $posts = Post::orderBy('created_at','desc')->take(5)->get();

        $postRes = Post::onlyTrashed()->get();

        $deletedPost = Post::onlyTrashed()->where('id','=',3)->get();

        $categories = Catogory::where('name','Furniture')->first();

        $post = Post::with('user')->get();
        $post->user->name();
    }
}
