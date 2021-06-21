@extends('layouts.app')

@section('content')
<h1>Data Selector page</h1>

<form action='/selectRows' method="POST" enctype="multipart/form-data">
    @csrf

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

    <div class="form-group">
        <label for="MLmodel">Model</label>
        <select class="form-control @error('MLmodel') is-invalid @enderror" name="MLmodel" id="MLmodel">
            @foreach ($model->getModelOptions() as $modelOption)
            <option value='{{$modelOption}}' <?= old('MLmodel') == '{{$modelOption}}' ? ' selected="selected"' : ''; ?>>{{$modelOption}}</option>
            @endforeach
        </select>
        @error('model')
        <div class="text-danger">
            {{$message}}
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mb-3">Submit</button>



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

            @endforeach
        </tbody>
    </table>



</form>


@endsection