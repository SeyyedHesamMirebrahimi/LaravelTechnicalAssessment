<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\FieldNeeded;
use App\Extensions\TokenUserProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignLabelToTask;
use App\Http\Requests\Api\LabelStoreRequest;
use App\Http\Requests\Api\TaskStoreRequest;
use App\Http\Resources\Api\LabelCollection;
use App\Http\Resources\Api\LabelResource;
use App\Http\Resources\Api\TaskCollection;
use App\Http\Resources\Api\TaskResource;
use App\Model\Label;
use App\Model\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    public $provider;
    public function __construct(TokenUserProvider  $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Display a listing of the resource.
     *
     * @param TokenUserProvider $provider
     * @param Request $request
     * @return Response
     */
    public function index( Request  $request): Response
    {
       $user= $this->provider->getUserByRequest($request);
        try {
            return response()->json([
                'success' => true,
                'data' =>   new TaskCollection($user->tasks()->get())
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ]);
        }
    }

    public function store(TaskStoreRequest $request ): JsonResponse
    {
        $user = $this->provider->getUserByRequest($request);
        $validated = $request->validated();
        try {
            $label = new Task();
            $label->title = $validated['title'];
            $label->user_id = $user->id;
            $label->description = $validated['description'];

            $label->save();
            return response()->json([
                'success' => true,
                'message' => 'Saved Successfully'
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id , Request  $request): JsonResponse
    {
       try{
           $task = Task::findOrFail($id);
           if ($task && $task->user_id != $this->provider->getUserByRequest($request)->id){
               return response()->json([
                   'success' => false,
                   'message' =>  'You Dont Have Access To This Task'
               ] , 403);
           }
           return response()->json([
               'success' => true,
               'data' =>   new TaskResource(Task::findOrFail($id))
           ]);
       }catch (\Exception $exception){
           return response()->json([
               'success' => false,
               'message' =>  $exception->getMessage()
           ],500);
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(TaskStoreRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        try {
            $task = Task::findOrFail($id);
            if ($task && $task->user_id != $this->provider->getUserByRequest($request)->id){
                return response()->json([
                    'success' => false,
                    'message' =>  'You Dont Have Access To This Task'
                ] , 403);
            }
            $task = Task::findOrFail($id);
            $task->title = $validated['title'];
            $task->description = $validated['description'];
            $task->update();
            return response()->json([
                'success' => true,
                'message' => 'Updated Successfully'
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $label = Task::find($id);
            $label->delete();
            return response()->json([
                'success' => true,
                'message' =>   'Deleted Successfully'
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }
    }

    /**
     * ChangeStatus Of The Task
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function status(int $id , Request  $request): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $user = $this->provider->getUserByRequest($request);
            if ($task && $task->user_id != $user->id){
                return response()->json([
                    'success' => false,
                    'message' =>  'You Dont Have Access To This Task'
                ] , 403);
            }
            if ($task->status){
                $task->status = 0;
//                send notification
                    Log::notice('Task With ID '. $task->id.' Closed');
                    Mail::to($user->email)->send(new \App\Mail\NotificationMail($user,$task));
            }else{
                $task->status = 1;
            }
            $task->update();
            return response()->json([
                'success' => true,
                'message' => 'Task Status Updated Successfully'
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }
    }

    /**
     * Add 1+n label to The Task
     */
    public function taskLabel(int $id , AssignLabelToTask $request): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $user = $this->provider->getUserByRequest($request);
            if ($task && $task->user_id != $user->id){
                return response()->json([
                    'success' => false,
                    'message' =>  'You Dont Have Access To This Task'
                ] , 403);
            }

            $labelsArray = $request['labels'];
            if (count($labelsArray) < 1){
                return response()->json([
                    'success' => true,
                    'message' => 'Label Arrays Is Empty'
                ], 400);
            }
            foreach ($labelsArray as $labelId) {
                $labelObject = Label::where('id' , $labelId)->first();
                if ($labelObject){
                    $task->labels()->attach($labelObject);
                }
            }
            $task->update();
            return response()->json([
                'success' => true,
                'message' => 'Task Labels Assigned Successfully'
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }

    }
}
