<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

class BaseController extends Controller
{
  function send_response(Collection|array|Authenticatable $data, string $message, int $code=201) : JsonResponse 
  {
    return response()->json([
      "data" => $data,
      "message" => $message,
    ], $code);   
  }

  function send_success(bool $data, string $message, int $code=201) : JsonResponse 
  {
    return response()->json([
      "data" => $data,
      "message" => $message,
    ], $code);   
  }

  function send_error(string $message, int $code=201) : JsonResponse 
  {
    return response()->json([
      "message" => $message,
    ], $code);   
  }
}