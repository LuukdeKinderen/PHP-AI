@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Result Page</h1>
    <h2>Settings</h2>
    <table class="table">

        <tr>
            <th scope="col">Classifier</th>
            <th scope="col">Split</th>
            <th scope="col">Y</th>
            <th scope="col">X</th>
        </tr>
        <tr>
            <td>{{$model->getClassifierStr()}}</td>
            <td>{{$model->getSplit()}}%</td>
            <td>{{$model->getY()}}</td>
            <td>
                <ul>
                    @foreach($model->getX() as $X)
                    <li>{{$X}}</li>
                    @endforeach
                </ul>
            </td>
        </tr>

    </table>
    <h2>Results</h2>
    <p>The model has an accuracy of <b>{{$accuracy*100}}%</b></p>
    <h3>Report</h3>
    <table class="table">
        <tr>
            <th>#</th>
            <th scope="col">Precision</th>
            <th scope="col">Recall</th>
            <th scope="col">F1score</th>
            <th scope="col">Support</th>
        </tr>
        @foreach($model->getUnparsedLabel() as $label)
        @if($label != '')
        <tr>
            <td>{{$label}}</td>
            <td>{{$report->getPrecision()[$label]*100}}%</td>
            <td>{{$report->getRecall()[$label]*100}}%</td>
            <td>{{$report->getF1score()[$label]*100}}%</td>
            <td>{{$report->getSupport()[$label]}}</td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td><b>Average</b></td>
            <td>{{$report->getAverage()['precision']*100}}%</td>
            <td>{{$report->getAverage()['recall']*100}}%</td>
            <td>{{$report->getAverage()['f1score']*100}}%</td>
            <td></td>
        </tr>

    </table>
    <a class="btn btn-primary" href="/again" role="button">Again</a>

</div>

@endsection