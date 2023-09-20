<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\Portofolio;
use App\Models\PortofolioImage;
use App\Models\PortofolioSkill;
use Auth;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

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

        $i = 0;
        foreach ($request->file('portofolio_images') as $image) {
            $fileName = strtolower('portofolio-' . $user->username . '-' . $porto->id . '-' . $i . '.' . $image->getClientOriginalExtension());
            $path = Storage::putFileAs(
                'portofolio-image', $image, $fileName
            );

            $portoImej = PortofolioImage::create([
                'portofolio_id' => $porto->id,
                'portofolio_image_url' => 'portofolio-image/' . $fileName,
                'is_thumbnail' => $i == 0 ? true : false,
            ]);
            $i++;
        }

        return new CommonResource(true, 'Portofolio Page Data Found!', $porto);
    }

    public function addDraftPortofolio(Request $request)
    {
        $user = Auth::user();
        $myLastSlug = Portofolio::where("user_id", $user->id)->orderBy('created_at', 'desc')->first()->slug;
        $slug = explode('-', $myLastSlug)[1] + 1;
        $newSlug = "{$user->id}-{$slug}";

        $porto = Portofolio::create([
            'user_id' => $user->id,
            'slug' => $newSlug,
            'is_draft' => true,
        ]);

        return new CommonResource(true, 'Portofolio Draft Has Been Created!', $porto);
    }

    public function prepareDraftPortofolio(Request $request)
    {
        $user = Auth::user();
        $myLastDraftExist = Portofolio::where("user_id", $user->id)
            ->where("is_draft", 1)->orderBy('created_at', 'desc')->first();

        if ($myLastDraftExist != null) {
            return new CommonResource(true, 'Portofolio Draft Has Been Loaded!', $myLastDraftExist);
        } else {
            $myLastSlug = Portofolio::where("user_id", $user->id)->orderBy('created_at', 'desc')->first()->slug;
            $slug = explode('-', $myLastSlug)[1] + 1;
            $newSlug = "{$user->id}-{$slug}";

            $porto = Portofolio::create([
                'user_id' => $user->id,
                'slug' => $newSlug,
                'is_draft' => true,
            ]);

            return new CommonResource(true, 'Portofolio Draft Has Been Created!', $porto);
        }
    }

    public function uploadPortofolioImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'portofolio_id' => 'required',
            'is_thumbnail' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        $porto = Portofolio::find($request->portofolio_id);
        $time = time();

        $fileName = strtolower("portfolio-{$porto->slug}-{$time}.{$request->image->extension()}");
        $path = Storage::putFileAs(
            'portofolio-image', $request->file('image'), $fileName
        );

        //Insert to DB
        $portoImg = PortofolioImage::create([
            'portofolio_id' => $request->portofolio_id,
            'portofolio_image_url' => 'portofolio-image/' . $fileName,
            'is_thumbnail' => $request->is_thumbnail,
        ]);

        return new CommonResource(true, 'Portofolio Image Uploaded!', $portoImg);
    }

    // public function saveDraftPortofolio(Request $request)
    // {
    //     $porto = Portofolio::find($request->portofolio_id);

    //     $porto->update([
    //         'portofolio_type_id' => $request->portofolio_type_id,
    //         'portofolio_name' => $request->portofolio_name,
    //         'portofolio_description' => $request->portofolio_description,
    //         'portofolio_url' => $request->portofolio_url,
    //         'portofolio_date' => $request->portofolio_date,
    //     ]);

    //     $portoSkills = explode(',', $request->portofolio_skills);
    //     foreach ($portoSkills as $skill) {
    //         $portoSikil = PortofolioSkill::create([
    //             'portofolio_id' => $porto->id,
    //             'skill_id' => $skill,
    //         ]);
    //     }

    //     return new CommonResource(true, 'Save Draft Success!', $porto);
    // }

    public function savePortofolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        $porto = Portofolio::find($request->portofolio_id);

        $porto->update([
            'portofolio_type_id' => $request->portofolio_type_id,
            'portofolio_name' => $request->portofolio_name,
            'portofolio_description' => $request->portofolio_description,
            'portofolio_url' => $request->portofolio_url,
            'portofolio_date' => $request->portofolio_date,
            'is_draft' => $request->is_draft,
        ]);

        if ($request->portofolio_skills) {
            $portoSkills = explode(',', $request->portofolio_skills);

            foreach ($portoSkills as $skill) {
                $portoSikil = PortofolioSkill::create([
                    'portofolio_id' => $porto->id,
                    'skill_id' => $skill,
                ]);
            };
        };

        return new CommonResource(true, 'Save Draft Success!', $porto);
    }

    public function deletePortofolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        //detele from s3
        $imgList = PortofolioImage::where("portofolio_id", $request->portofolio_id)->get();
        foreach ($imgList as $img) {
            $path = Storage::delete(
                $img->portofolio_image_url
            );
        };

        //delete from DB
        $porto = Portofolio::find($request->portofolio_id);
        $porto->delete();

        return new CommonResource(true, 'Portofolio Has Been Deleted!', null);
    }

    public function testUpload(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'image1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // $fileName = strtolower('main-image' . '.' . $request->image1->extension());
        // $path = Storage::putFileAs(
        //     'portofolio-image', $request->file('image1'), $fileName
        // );

        // return new CommonResource(true, 'Portofolio Page Data Found!', $path);

        //
        // $myLastSlug = Portofolio::where("user_id", 1)->orderBy('created_at', 'desc')->first()->slug;
        // $slug = explode('-', $myLastSlug)[1] + 1;

        //
        $imgList = PortofolioImage::where("portofolio_id", 7)->get();
        foreach ($imgList as $img) {
            $path = Storage::delete(
                $img->portofolio_image_url
            );
        };
        return new CommonResource(true, 'Portofolio Page Data Found!', $imgList);
    }

    public function updateHome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new CommonResource(false, $validator->errors(), null), 422);
        }

        $user = Auth::user();

        if ($request->profile_picture_url && $request->profile_picture_url != 'undefined') {
            //$needToDelete = $user->profile_picture_url;
            $time = time();
            $fileName = strtolower("avatar-{$user->id}-{$time}.{$request->profile_picture_url->extension()}");
            $path = Storage::putFileAs(
                'avatar', $request->file('profile_picture_url'), $fileName
            );
            if ($path) {
                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'headline' => $request->headline,
                    'profile_picture_url' => $fileName,
                ]);

                // $deletePath = Storage::delete(
                //     $needToDelete
                // );
            }
        } else {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'headline' => $request->headline,
            ]);
        }

        return new CommonResource(true, 'Update Home Success!', $user);
    }

    public function updateAbout(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'about_me' => $request->about_me,
        ]);

        return new CommonResource(true, 'Update About Success!', $user);
    }
}
