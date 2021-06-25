@extends('layouts.app')

@section('content')
<h1>Data Selector page</h1>
<h2>Step 2</h2>
<p>Select a classification model and define the percentage that becomes test data.</p>
<form action='/selectRows' method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="MLmodel">Classifier</label>
        <select class="form-control @error('MLmodel') is-invalid @enderror" name="MLmodel" id="MLmodel">
            @foreach ($model->getClassifierOptions() as $modelOption)
            <option value='{{$modelOption}}' <?= old('MLmodel') == '{{$modelOption}}' ? ' selected="selected"' : ''; ?>>{{$modelOption}}</option>
            @endforeach
        </select>
        @error('model')
        <div class="text-danger">
            {{$message}}
        </div>
        @enderror
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



    <h2>Step 3</h2>
    <p>Select which columns you want to use. Here 'drop' is removed, 'Y' becomes the label that is predicted and 'X' are the attributes used to make the prediction. Missing data is filled with the average value when the column is numeric. If this is not possible then the most frequent value from the column is used. </p>

    <table class="table table-striped table-bordered table-sm">

        <tbody>
            @foreach ($model->getData() as $rowKey => $row)

            <tr>

                @if($rowKey == 0)

                @foreach ($row as $key => $value)
                <th scope="col">
                    {{ $value }}
                    <select class="form-control" name="{{ $value }}" id="{{ $value }}">
                        <option value='drop'>Drop</option>
                        <option value='x'>X</option>
                        <option value='y'>Y</option>
                    </select>
                </th>
                @endforeach
                @else

                @foreach ($row as $key => $value)
                <td scope="col">{{ $value }}</td>
                @endforeach
                @endif
            </tr>
            @if($rowKey == 0)
            <tr>
                <td colspan="{{count($row)}}">
                    <div class="d-flex justify-content-center">
                        <h2>Step 4</h2>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Make a prediction!</button>
                    </div>
                </td>

            </tr>
            @endif
            @endforeach
        </tbody>
    </table>



</form>


@endsection