@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Model settings</h1>
    <form action='/model' method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label" for="customFile">Upload .csv file</label>
            <input type="file" class="form-control @error('csv') is-invalid @enderror" id="csv" name="csv" accept=".csv">
        </div>

        <div class="form-group">
            <label for='split'>Split</label>
            <div class="input-group">
                <input class="form-control @error('split') is-invalid @enderror" type="number" value="{{old('split') ?? $model->getSplit()}}" name="split" id="split">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            @error('split')
            <div class="text-danger">
                {{$message}}
            </div>
            @enderror
        </div>

        <script>
            console.log("test");
        </script>

        <div class="form-group">
            <label for="model">Model</label>
            <select class="form-control @error('model') is-invalid @enderror" name="model" id="model">
                @foreach ($model->getModelOptions() as $modelOption)
                <option value='{{$modelOption}}' <?= old('model') == '{{$modelOption}}' ? ' selected="selected"' : ''; ?>>{{$modelOption}}</option>
                @endforeach
            </select>
            @error('model')
            <div class="text-danger">
                {{$message}}
            </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection