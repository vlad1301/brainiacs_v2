@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <button type="button"  class="btn btn-primary btn-lg" onclick="window.location='{{ route("set_project") }}'">Add Project</button>
                        <button type="button"  class="btn btn-primary btn-lg" onclick="window.location='{{ route("view_projects") }}'">View projects</button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
