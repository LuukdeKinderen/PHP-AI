<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLearningModel extends Model
{
    use HasFactory;

    public $split = 33;
    public $selectedModel;

    public $data;
    public $colNames;

    public $modelOptions = [
        'SVC',
        'k-Nearest Neighbors',
        'Naive Bayes',
        'SVR',
        'k-Means'
    ];

    public function getModelOptions()
    {
        return $this->modelOptions;
    }

    public function getSplit()
    {
        return $this->split;
    }

    public function getModel()
    {
        return $this->selectedModel;
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

    public function setSplit($split){
        $this->split = $split;
    }

    public function setModel($selectedModel){
        $this->selectedModel = $selectedModel;
    }

    // public function removeColNames(){
    //     echo(gettype($data));
    //     // array_shift($data);
    // }

}
