<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    function index() : JsonResponse {
       $items = Item::all(); 
       return response()->json([
        'status' => true,
        'data' => $items
       ]);
    }

    function show(int $id) : JsonResponse {
       $items = Item::findOrFail($id); 

       return response()->json([
        'status' => true,
        'data' => $items
       ]);
    }

    function filter(Request $request) 
    {
        $filter = Item::where('name', 'like', $request->item . '%')->get();
        return $filter;
    }
}
