<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\Portofolio;
//use App\Models\PortofolioImage;
use App\Models\PortofolioSkill;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortofolioController extends Controller
{
    public function getMyPortofolio()
    {
        $myPortofolio = Auth::user()->portofolios();
        return new CommonResource(true, 'My Portofolio Data Found!', $myPortofolio);
    }

    public function addMyPortofolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portofolio_type_id' => 'required',
            'portofolio_name' => 'required',
            'portofolio_skills' => 'required',
            //'portofolio_images' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        $user = Auth::user();

        $porto = Portofolio::create([
            'user_id' => $user->id,
            'portofolio_type_id' => $request->portofolio_type_id,
            'portofolio_name' => $request->portofolio_name,
            'portofolio_description' => $request->portofolio_description,
            'portofolio_url' => $request->portofolio_url,
            'portofolio_date' => $request->portofolio_date,
        ]);

        $portoSkills = explode(',', $request->portofolio_skills);
        foreach ($portoSkills as $skill) {
            $portoSikil = PortofolioSkill::create([
                'portofolio_id' => $porto->id,
                'skill_id' => $skill,
            ]);
        }

        // $i = 0;
        // foreach ($request->portofolio_images as $image) {
        //     $portoImej = PortofolioImage::create([
        //         'portofolio_id' => $porto->id,
        //         'portofolio_image_url' => $image->url,
        //         'is_thumbnail' => $i == 0 ? true : false,
        //     ]);
        //     $i++;
        // }

        return new CommonResource(true, 'Portofolio Page Data Found!', $porto);
    }
}
