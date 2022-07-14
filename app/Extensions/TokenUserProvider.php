<?php

namespace App\Extensions;




use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;

use App\User;
//use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;

class TokenUserProvider
{
    /**
     * @param Request $request
     * @return mixed|null
     */
    public function getUserByRequest(Request $request)
    {
        if ($request->headers->get('authorization')) {
            $token = str_replace('Bearer ' , '' , $request->headers->get('authorization'));
            $user = User::all()->where('token' , $token)->first();
            if ($user) {
                return $user;
            } else {
                return null;
            }
        }
        return  null;

    }


}
