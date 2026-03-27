<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(){
        return Training::all();
    }

    public function store(Request $request){
         

    $training = Training::create([
        'user_id' => 1,
        'activity_type' => $request->activity_type,
        'distance' => $request->distance,
        'duration' => $request->duration,
        'training_date' => $request->training_date,
        
        ]);

        return response()->json($training,201);

    }
}
