@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Result Page</h1>
    <h2>Model</h2>
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">Split</th>
                <th scope="col">Model</th>
            </tr>
            <tr>
                <td>{{$model->getSplit()}}</td>
                <td>{{$model->getModel()}}</td>
            </tr>
        </thead>
    </table>

</div>

@endsection