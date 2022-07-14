@extends('layouts.panel')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Labels</h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Label</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($labels as $label)
                                <tr>
                                    <td>{{$label->id}}</td>
                                    <td>{{$label->name}}</td>
                                    <td>
                                        <a href="{{ route('label.edit', $label->id)}}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('label.destroy', $label->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('label.create')}}" class="btn btn-success">Create</a>
@endsection
