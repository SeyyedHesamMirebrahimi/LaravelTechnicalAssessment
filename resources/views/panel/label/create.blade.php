@extends('layouts.panel')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Create Label</h5>
                <div class="card-body">
                    <form method="post" action="{{ route('label.store' ) }}">
                        <div class="form-group">
                            @csrf
                            <label for="label_name">Name:</label>
                            <input type="text" class="form-control" name="name" />
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
