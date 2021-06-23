<?php

namespace App\Http\Controllers;

use App\Models\MachineLearningModel;
use App\Models\CsvImportRequest;
use Illuminate\Http\Request;

use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;

use Phpml\Classification\KNearestNeighbors;
use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\ArrayDataset;

class ModelController extends Controller
{
    public function index()
    {
        $model = new MachineLearningModel();

        return view('prediction.modelSelecter', [
            'model' => $model
        ]);
    }

    public function uploadCSV(CsvImportRequest $request)
    {

        $path = $request->file('csv_data')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        session_start();
        $_SESSION['csv_data'] = $data;

        $model = new MachineLearningModel();
        $model->setData($data);

        return view('model.dataSelector', ['model' => $model]);
    }

    public function selectRows(Request $request)
    {

        // $this->validate($request,[
        //     'split' => 'required',
        //     'MLmodel' => 'required',
        // ]);


        session_start();

        $data = $_SESSION['csv_data'];

        $model = new MachineLearningModel();


        $model->setColNames($data[0]);
        array_shift($data);
        $model->setData($data);

        $model->setSplit($request->input('split'));
        $model->setModel($request->input('MLmodel'));

        $colOptions = array();
        foreach ($model->getColNames() as $ind => $col) {
            $colOptions[$ind] = $request->input($col);
        }

        $parseOptions = array();

        $x = array();
        $y = array();
        foreach ($model->getData() as $rowInd => $row) {
            $localX = array();
            $xCount = 0;

            $localY = "";
            // $yCount = 0;


            foreach ($row as $ind => $val) {
                if ($colOptions[$ind] == 'x') {

                    $parsedVal = $val;

                    if(gettype($parsedVal) != 'integer'){
                        $parsedVal = (int)$parsedVal;
                        // if(array_key_exists($ind, $parseOptions)){

                        // } else {
                            
                        // }
                    }
                    $localX[$xCount] = $parsedVal;
                    $xCount += 1;
                } elseif ($colOptions[$ind] == 'y') {
                    $localY = $val;
                    // $localY[$yCount] = $val;
                    // $yCount += 1;
                }
            }

            $x[$rowInd] = $localX;
            $y[$rowInd] = $localY;
        }



        $dataset = new ArrayDataset($x, $y);
        // $dataset = new ArrayDataset($samples,$labels);
        $dataset = new RandomSplit($dataset, 0.3, 1234);

        // train group
        $dataset->getTrainSamples();
        $dataset->getTrainLabels();


        $classifier = new KNearestNeighbors();
        $classifier->train($dataset->getTrainSamples(), $dataset->getTrainLabels());


        // // test group
        // $dataset->getTestSamples();
        // $dataset->getTestLabels();

        // $prediction = $classifier->predict($dataset->getTestSamples());
        $actual = $dataset->getTestLabels();


        dd($x, $y,//$samples,$labels, $dataset->getTestSamples()[0],

       $classifier->predict($dataset->getTestSamples()),$actual
      //   $colOptions, $request, $model->getData()[0],  $x, $y
        );
    }
}
