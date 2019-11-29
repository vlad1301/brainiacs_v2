@foreach($projects as $project)
    <h3>{{ $project->projectName }}</h3>
    <p>{{ $project->projectUrl}}</p>
    <p>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info">View project</a>
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary">Edit project</a>
    </p>
    <hr>
@endforeach
