<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Catogory;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    //
    public function query()
    {
        //1
        $posts = Post::all();

        $users = User::where('age' , '>' , 30)->get();

        $products = Product::orderBy('created_at','desc')
        ->take(10)->get();

        //2
        $orders = Order::where('status','=','completed')
        ->where('total_price','>',1000)->get();

        $customers = Customer::where('name','LIKE','%John%')
        ->orWhere('email' , 'LIKE' ,'%gmail.com')->get();

        $employees = Employee::whereIn('department_id',[5,7])
        ->where('salary','>=',3000)->get();


        //3
        $posts = Post::where('status','=','published')
        ->with('comments')
        ->get();

        $admins = User::whereHas('roles',function($query){
            $query->where('name','admin');
        })->with('roles')->get();

        $categories = Catogory::whereHas('products',function($query){
            $query->where('price','<',500);
        })->with('products')->get();

        //4
        $userCount = User::whereMonth('created_at',Carbon::now()->month)
        ->count();

        $avgPrice = Product::where('category_id','=',3)
        ->average('price');

        //5
        User::where('login_time',Carbon::now()->month)
        ->whereNotNull('login_time')
        ->update([
            'is_active' => true
        ]);

        $posts = Post::with('comments')->where('status','=','draft')->get();
        foreach($posts as $post)
        {
            $post->comments->delete();
        }

        Product::where('quantity','=',0)->update([
            'quantity' => 50
        ]);

        //6
        $id = 1;
        $orders = Customer::with('orders.products')->find($id);

        $years = Carbon::now()->subYears(5);
        $authors = Author::with(['books' => function($query) use ($years){
            $query->where('published_at','>=',$years);
        }])->get();

        $invoices = Customer::with(['invoices.payments' => function($query){
            $query->where('amount','>=',500);
        }])->find($id);
    }
}
