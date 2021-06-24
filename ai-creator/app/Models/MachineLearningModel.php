<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLearningModel extends Model
{
    use HasFactory;

    public $split = 33;
    public $classifier;

    public $data;
    public $colNames;
    public $colOptions;

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

    public function setData($data){
        $this->data = $data;
    }

    public function getColNames(){
        return $this->colNames;
    }

    public function setColNames($colNames){
        $this->colNames = $colNames;
    }

    public function getSplit(){
        return $this->split;
    }

    public function setSplit($split){
        $this->split = $split;
    }

    public function setClassifier($classifier){
        $this->$classifier = $classifier;
    }

    public function setColOptions($colOptions){
        $this->colOptions = $colOptions;
    }




}
