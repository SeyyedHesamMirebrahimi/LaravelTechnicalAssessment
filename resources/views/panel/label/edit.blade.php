@extends('layouts.panel')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Edit Label</h5>
                <div class="card-body">
                    <form method="post" action="{{ route('label.update' , $label->id ) }}">
                        <div class="form-group">
                            @csrf
                            @method('PATCH')
                            <label for="label_name">Name:</label>
                            <input type="text" value="{{$label->name}}" class="form-control" name="name" />
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
