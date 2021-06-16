@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Model settings</h1>
    <form action='/model' method="POST">
        @csrf
        <div class="form-group has-validation">
            <label for='split'>Split</label>
            <div class="input-group">
                <input class="form-control @error('split') is-invalid @enderror" type="number" value="{{old('split')}}" name="split" id="split">
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

        {{old('model')}}
        <div class="form-group">
            <label for="model">Model</label>
            <select class="form-control @error('model') is-invalid @enderror" name="model" id="model">
                <option value='1' <?=old('model') == '1' ? ' selected="selected"' : '';?>>SVC</option>
                <option value='2' <?=old('model') == '2' ? ' selected="selected"' : '';?>>k-Nearest Neighbors</option>
                <option value='3' <?=old('model') == '3' ? ' selected="selected"' : '';?>> Naive Bayes</option>
                <option value='4' <?=old('model') == '4' ? ' selected="selected"' : '';?>>SVR</option>
                <option value='5' <?=old('model') == '5' ? ' selected="selected"' : '';?>>k-Means</option>
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