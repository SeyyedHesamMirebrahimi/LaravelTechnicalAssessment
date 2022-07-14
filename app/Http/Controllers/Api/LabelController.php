<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\FieldNeeded;
use App\Extensions\TokenUserProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LabelStoreRequest;
use App\Http\Resources\Api\LabelCollection;
use App\Http\Resources\Api\LabelResource;
use App\Model\Label;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * hasTest
     */
    public function index(): Response
    {
//       @important: project owner doesnt mention that label list should just be the user labels....
        try {
            return response()->json([
                'success' => true,
                'data' =>   new LabelCollection(Label::all())
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' =>  $exception->getMessage()
            ],500);
        }
    }

    /**
     * @param LabelStoreRequest $request
     * @param TokenUserProvider $provider
     * @return JsonResponse
     * hasTest
     */
    public function store(LabelStoreRequest $request , TokenUserProvider  $provider): JsonResponse
    {
        $validated = $request->validated();
        try {
            if (Label::where('name' , $validated['name'])->first()){
                return response()->json([
                    'success' => false,
                    'message' => 'Label Name Already Exist'
                ], 400);
            }
            Label::create($request->validated());
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
     * @return JsonResponse
     * has Test
     */
    public function show(int $id): JsonResponse
    {
       try{
           return response()->json([
               'success' => true,
               'data' =>   new LabelResource(Label::find($id))
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
     * @param LabelStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LabelStoreRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        try {
            if (Label::where('name' , $validated['name'])->first()){
                return response()->json([
                    'success' => false,
                    'message' => 'Label Name Already Exist'
                ], 400);
            }
            $label = Label::where('id' , $id)->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Updated Successfully'
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * has Test
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $label = Label::find($id)->delete();
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
}
