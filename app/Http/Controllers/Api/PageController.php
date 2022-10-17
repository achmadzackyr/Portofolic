<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function getPortofolioPageByUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        $user = User::where('username', $request['username'])->first();
        $porotofolio = Portofolio::with(['portofolio_type', 'portofolio_images'])
            ->with(['portofolio_skills' => function ($query) {
                $query->with('skill');

            }])->where('user_id', $user->id)->get();

        $response = [
            'user' => $user,
            'porotofolio' => $porotofolio,
        ];
        return new CommonResource(true, 'Portofolio Page Data Found!', $response);
    }
}
