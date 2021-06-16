<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(){
        return view('prediction.modelSelecter');
    }

    public function predict(Request $request){

        //  dd($request);

        $this->validate(
            $request, [
                'split' => 'required|integer|between:1,100',
                'model' => 'required'
            ]
            );

        return view('prediction.result');
    }
}
