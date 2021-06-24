<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;

class MachineLearningModel extends Model
{
    use HasFactory;

    public $split = 33;
    public $classifier;

    public $data;
    public $colNames;
    public $colOptions;

    public $parseOptions;


    public function needToParse()
    {
        $needToParse = false;
        foreach ($this->colOptions as $colInd => $option) {
            if ($option == 'y') {
                $needToParse = !$this->parseOptions[$colInd]['numeric'];
            }
        }
        return $needToParse;
    }

    public function getUnparsedLabel()
    {
        foreach ($this->colOptions as $colInd => $option) {
            if ($option == 'y') {
                $parseOption = $this->parseOptions[$colInd]['parseOptions'];
            }
        }
        return $parseOption;
    }

    public function getY()
    {
        $Y = "";
        foreach ($this->colOptions as $colInd => $option) {
            if ($option == 'y') {
                $Y = $this->colNames[$colInd];
            }
        }
        return $Y;
    }

    public function getX()
    {
        $X = array();
        $count = 0;
        foreach ($this->colOptions as $colInd => $option) {
            if ($option == 'x') {
                $X[$count] = $this->colNames[$colInd];
                $count += 1;
            }
        }
        return $X;
    }

    public function getClassifierOptions()
    {
        return [
            'k-Nearest Neighbors',
            'Naive Bayes'
        ];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getColNames()
    {
        return $this->colNames;
    }

    public function setColNames($colNames)
    {
        $this->colNames = $colNames;
    }

    public function getSplit()
    {
        return $this->split;
    }

    public function setSplit($split)
    {
        $this->split = $split;
    }

    public function setClassifier($classifier)
    {
        $this->classifier = $classifier;
    }

    public function getClassifierStr()
    {
        return $this->classifier;
    }

    public function getClassifier()
    {
        if ($this->classifier == 'k-Nearest Neighbors') {
            return new KNearestNeighbors();
        } elseif ($this->classifier == 'Naive Bayes') {
            return new NaiveBayes();
        }
    }

    public function setColOptions($colOptions)
    {
        $this->colOptions = $colOptions;
    }

    public function createParseOptions()
    {
        $values = array();
        $uniqueValues = array();
        $parseOptions = array();

        foreach ($this->getColNames() as $colInd => $colname) {
            $values[$colInd] = array();
            $uniqueValues[$colInd] = array();
            $parseOptions[$colInd] = array();
        }

        foreach ($this->getData() as $rowInd => $row) {
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
            $parseOptions[$colInd]['parseOptions'] = $uniqueValues[$colInd];
        }

        $this->parseOptions = $parseOptions;
    }

    public function getCleanedData()
    {
        $x = array();
        $y = array();
        foreach ($this->getData() as $rowInd => $row) {
            $localX = array();
            $xCount = 0;

            $localY = "";
            // $yCount = 0;


            foreach ($row as $ind => $val) {
                if ($this->colOptions[$ind] == 'x') {

                    $parsedVal = $val;

                    if ($parsedVal == '') {
                        $parsedVal = $this->parseOptions[$ind]['mean'];
                    }

                    if ($this->parseOptions[$ind]['numeric']) {
                        $parsedVal = (float)$parsedVal;
                    } else {
                        $parsedVal = array_search($parsedVal, $this->parseOptions[$ind]['parseOptions']);
                    }

                    // $parsedVal = (float)$parsedVal;

                    $localX[$xCount] = $parsedVal;
                    $xCount += 1;
                } elseif ($this->colOptions[$ind] == 'y') {

                    $parsedVal = $val;

                    if ($parsedVal == '') {
                        $parsedVal = $this->parseOptions[$ind]['mean'];
                    }

                    if ($this->parseOptions[$ind]['numeric']) {
                        $parsedVal = (int)$parsedVal;
                    } else {
                        $parsedVal = array_search($parsedVal, $this->parseOptions[$ind]['parseOptions']);
                    }
                    $localY = $parsedVal;
                }
            }

            $x[$rowInd] = $localX;
            $y[$rowInd] = $localY;
        }
        return [$x, $y];
    }
}
