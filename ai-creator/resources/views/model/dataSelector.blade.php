@extends('layouts.app')

@section('content')
<h1>Data Selector page</h1>

<form action='/selectRows' method="POST" enctype="multipart/form-data">
    @csrf

    <button type="submit" class="btn btn-primary">Submit</button>
    
   

    <table class="table table-striped table-bordered table-sm">

        <tbody>
            @foreach ($_SESSION['csv_data'] as $rowKey => $row)

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