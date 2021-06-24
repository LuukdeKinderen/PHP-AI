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
use Phpml\Metric\Accuracy;
use Phpml\Metric\ClassificationReport;
use Phpml\Metric\ConfusionMatrix;
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

    public function tryAgain(){
        session_start();
        $data = $_SESSION['csv_data'];

        $model = new MachineLearningModel();
        $model->setData($data);

        return view('model.dataSelector', ['model' => $model]);
    }

    public function selectRows(Request $request)
    {

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
        $parseOptions = $model->createParseOptions();
        [$x, $y] =  $model->getCleanedData($parseOptions);




        // create dataset
        $dataset = new ArrayDataset($x, $y);
        $dataset = new RandomSplit($dataset, $model->getSplit() / 100, 1234);
        // $actualdataset = new ArrayDataset($x, $y);
        // $dataset = new RandomSplit($dataset, $model->getSplit() / 100, 1234);

        $classifier = $model->getClassifier();


        $classifier->train($dataset->getTrainSamples(), $dataset->getTrainLabels());


        $unparsedLables = $model->getUnparsedLabel();


        // dd($unparsedLables);

        $actual = $dataset->getTestLabels();
        if ($model->needToParse()) {
            foreach ($actual as $actualInd => $actualVal) {
                $actual[$actualInd] = $unparsedLables[$actualVal];
            }
        }


        $predicted = $classifier->predict($dataset->getTestSamples());
        if ($model->needToParse()) {
            foreach ($predicted as $predictedInd => $predictedVal) {
                $predicted[$predictedInd] = $unparsedLables[$predictedVal];
            }
        }
        $report = new ClassificationReport($actual, $predicted);

        return view('prediction.result', [
            'model' => $model,
            'accuracy' => Accuracy::score($actual, $predicted),
            'report' => $report,
        ]);

        // $mappedMatrix = array();
        // foreach ($matrix as $matrixColInd => $matrixCol) {
        //     $mappedMatrix[$unparsedLables[$matrixColInd]] = array();
        //     foreach ($matrixCol as $matrixRowInd => $matrixRow) {
        //         echo ($unparsedLables[$matrixRowInd]);
        //         echo ($matrixRow);

        //         // $mappedMatrix[$unparsedLables[$matrixColInd]][$unparsedLables[$matrixRowInd]] = $matrixRow;
        //         // $matrixCol[$unparsedLables[$matrixRowInd]] = $matrixRow;
        //     }
        // }
        dd(
            // $x,
            // $y, //$samples,$labels, $dataset->getTestSamples()[0],

            $predicted,
            $actual,
            Accuracy::score($actual, $predicted),
            // $mappedMatrix,
            // $matrix,
            $report,
            $unparsedLables
            // $actual
            //   $colOptions, $request, $model->getData()[0],  $x, $y
        );
    }
}
