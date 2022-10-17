<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\PortofolioType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortofolioTypeController extends Controller
{
    public function index()
    {
        $portofolioTypes = PortofolioType::latest()->paginate(10);

        //return collection of posts as a resource
        return new CommonResource(true, 'PortofolioType Lists', $portofolioTypes);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'portofolio_type_name' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $portofolioType = PortofolioType::create([
            'portofolio_type_name' => $request->portofolio_type_name,
        ]);

        //return response
        return new CommonResource(true, 'PortofolioType Successfully Added!', $portofolioType);
    }

    public function show(PortofolioType $portofolioType)
    {
        //return single post as a resource
        return new CommonResource(true, 'PortofolioType Found!', $portofolioType);
    }

    public function update(Request $request, PortofolioType $portofolioType)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'portofolio_type_name' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $portofolioType->update([
            'portofolio_type_name' => $request->portofolio_type_name,
        ]);

        //return response
        return new CommonResource(true, 'PortofolioType Successfully Updated!', $portofolioType);
    }

    public function destroy(PortofolioType $portofolioType)
    {
        //delete post
        $portofolioType->delete();

        //return response
        return new CommonResource(true, 'PortofolioType Successfully Deleted!', null);
    }
}
