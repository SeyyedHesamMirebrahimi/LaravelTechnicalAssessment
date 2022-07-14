<?php

namespace App\Http\Controllers;

use App\Model\Label;
use App\Model\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function dev()
    {
        var_dump(Label::all()->first()->id);die;
        $user = User::where('email' , 'hesaam.mir@gmail.com')->first();
        for ($x=1 ; $x<=10 ; $x++){
            $label = new Label();
            $label->name = 'New3ed Label '.$x;
            $label->save();
        }

        for ($x=1 ; $x<=10 ; $x++){
            $task = new Task();
            $task->title = 'Task Name '.$x;
            $task->description = 'Task Description '.$x;
            $task->user_id = $user->id;
            $task->save();
            foreach (Label::all()  as  $label) {
                $task->labels()->attach($label);
           }

            $task->update();
        }
        echo 's';die;





    }
}
