<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::latest()->paginate(5);

        //return collection of posts as a resource
        return new SkillResource(true, 'Skill Lists', $skills);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'skill_name' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $skill = Skill::create([
            'skill_name' => $request->skill_name,
        ]);

        //return response
        return new SkillResource(true, 'Skill Successfully Added!', $skill);
    }

    public function show(Skill $skill)
    {
        //return single post as a resource
        return new SkillResource(true, 'Skill Found!', $skill);
    }

    public function update(Request $request, Skill $skill)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'skill_name' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $skill->update([
            'skill_name' => $request->skill_name,
        ]);

        //return response
        return new SkillResource(true, 'Skill Successfully Updated!', $skill);
    }

    public function destroy(Skill $skill)
    {
        //delete post
        $skill->delete();

        //return response
        return new SkillResource(true, 'Skill Successfully Deleted!', null);
    }
}
