<?php

namespace App\Http\Controllers;

use App\Models\MachineLearningModel;
use App\Models\CsvImportRequest;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index()
    {
        $model = new MachineLearningModel();

        return view('prediction.modelSelecter', [
            'model' => $model
        ]);
    }

    // public function predict(CsvImportRequest $request)
    // {
    //     // dd($request->csv);
        
    //     $path = $request->file('csv')->getRealPath();
    //     $data = array_map('str_getcsv', file($path));


    //     $model = new MachineLearningModel();

    //     $model->setAll(
    //         $request->split,
    //         $request->model,
    //         $data
    //     );


    //     return view('prediction.result', [
    //         'model' => $model
    //     ]);
    // }

    public function uploadCSV(CsvImportRequest $request){

        $path = $request->file('csv_data')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        session_start();
        $_SESSION['csv_data']=$data;

        return view('model.dataSelector');
    }

    public function selectRows(Request $request){

        dd($request);

    }
}
