<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Limelight\Limelight;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('furigana', function (Request $request) {
    if (empty($request->kalimats)) {
        return response()->json([
            'code' => 400,
            'message' => 'kalimat tidak boleh kosong',
        ], 400);
    }

    $kalimat = $request->kalimats;
    if(!is_array($kalimat)) {
        $kalimat = [
            'id' => null,
            'kalimat' => $kalimat
        ];
    }

    foreach ($kalimat as $i => $kal)
    {
        foreach ($kal['kalimat'] as $j => $k)
        {
            $limelight = new Limelight();
            $results = $limelight->parse($k);
            $kalimat[$i]['kalimat'][$j] = $results->string('furigana');
        }
    }

    return response()->json([
        'code' => 200,
        'furigana' => $kalimat,
    ]);
});
