<?php

// require '../../../vendor/autoload.php';

namespace App\Http\Controllers;

use App\Models\MachineLearningModel;
use App\Models\CsvImportRequest;
use Illuminate\Http\Request;
use Phpml\Association\Apriori;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;

use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
use Phpml\Classification\SVC;
use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\ArrayDataset;
use Phpml\Regression\LeastSquares;

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
        $model->setClassifier($request->input('MLmodel'));

        $colOptions = array();
        foreach ($model->getColNames() as $ind => $col) {
            $colOptions[$ind] = $request->input($col);
        }

        $model->setColOptions($colOptions);



        $values = array();
        $uniqueValues = array();
        $parseOptions = array();

        foreach ($model->getColNames() as $colInd => $colname) {
            $values[$colInd] = array();
            $uniqueValues[$colInd] = array();
            $parseOptions[$colInd] = array();
        }

        foreach ($model->getData() as $rowInd => $row) {
            foreach ($row as $ind => $val) {

                array_push($values[$ind], $val);

                if (!in_array($val, $uniqueValues[$ind])) {
                    array_push($uniqueValues[$ind], $val);
                }
            }
        }

        foreach ($uniqueValues as $colInd => $col) {
            $numeric = true;
            foreach ($uniqueValues[$colInd] as $val) {
                if (!is_numeric($val)) {
                    $numeric = false;
                }
            }

            $mean = '';
            if ($numeric) {
                $filterd = array_filter($values[$colInd]);
                $mean = array_sum($filterd) / count($filterd);
            } else {

                $valueCounts = array_count_values($values[$colInd]);
                arsort($valueCounts);
                $mean = array_keys($valueCounts)[0];
            }

            $parseOptions[$colInd]['numeric'] = $numeric;
            $parseOptions[$colInd]['mean'] = $mean;
        }

        // dd($values, $parseOptions, $uniqueValues);



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

                    if ($parsedVal == '') {
                        $parsedVal = $parseOptions[$ind]['mean'];
                    }

                    if ($parseOptions[$ind]['numeric']) {
                        $parsedVal = (float)$parsedVal;
                    } else {
                        $parsedVal = array_search($parsedVal, $uniqueValues[$ind]);
                    }

                    // $parsedVal = (float)$parsedVal;

                    $localX[$xCount] = $parsedVal;
                    $xCount += 1;
                } elseif ($colOptions[$ind] == 'y') {

                    $parsedVal = $val;

                    if ($parsedVal == '') {
                        $parsedVal = $parseOptions[$ind]['mean'];
                    }

                    if ($parseOptions[$ind]['numeric']) {
                        $parsedVal = (int)$parsedVal;
                    } else {
                        $parsedVal = array_search($parsedVal, $uniqueValues[$ind]);
                    }

                    $localY = $parsedVal;
                    // $localY[$yCount] = $val;
                    // $yCount += 1;
                }
            }

            $x[$rowInd] = $localX;
            $y[$rowInd] = $localY;
        }

        // dd($x, $y);

        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $dataset = new ArrayDataset($x, $y);
        // $dataset = new ArrayDataset($samples,$labels);
        $dataset = new RandomSplit($dataset, 0.3, 1234);

        // train group
        $dataset->getTrainSamples();
        $dataset->getTrainLabels();


        // $classifier = new KNearestNeighbors();
        $classifier = new NaiveBayes();
        $classifier->train($dataset->getTrainSamples(), $dataset->getTrainLabels());


        // // test group
        // $dataset->getTestSamples();
        // $dataset->getTestLabels();

        // $prediction = $classifier->predict($dataset->getTestSamples());
        $actual = $dataset->getTestLabels();


        dd(
            $x,
            $y, //$samples,$labels, $dataset->getTestSamples()[0],

            $classifier->predict($dataset->getTestSamples()),
            $actual
            // $actual
            //   $colOptions, $request, $model->getData()[0],  $x, $y
        );
    }
}
