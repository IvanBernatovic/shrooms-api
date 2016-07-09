<?php

namespace App\Http\Controllers\Api;

use App\Models\Mushroom;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MushroomController extends Controller
{
    public function index()
    {
        return 'all mushrooms';
    }

    public function store(Request $request)
    {
        return Mushroom::create($request->all());
    }
}
