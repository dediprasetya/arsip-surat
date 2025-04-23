<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratApiController extends Controller
{
    public function index()
    {
        return response()->json(Surat::all());
    }

    public function show($id)
    {
        return response()->json(Surat::findOrFail($id));
    }
}
