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

    public function setAll($split, $selectedModel){
        $this->split = $split;
        $this->selectedModel = $selectedModel;
    }
}
