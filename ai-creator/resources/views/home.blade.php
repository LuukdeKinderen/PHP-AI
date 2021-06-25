@extends('layouts.app')

@section('content')

<h1>Home page</h1>
<p>On this site you can quickly test which classifier works best for your data. You can also quickly select or deselect many different columns and thus test what the best combination is.</p>
<h2>Step 1</h2>
<form action='/csv' method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label" for="customFile">Upload .csv file</label>
            <input type="file" class="form-control @error('csv_data') is-invalid @enderror" id="csv_data" name="csv_data" accept=".csv">
        </div>

        <button type="submit" class="btn btn-primary">Upload file</button>
</form>

@endsection