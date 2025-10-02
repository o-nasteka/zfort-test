<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actor Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Actor Submissions</h3>
                        <a href="{{ route('actors.form') }}" class="btn btn-primary">Add New Actor</a>
                    </div>
                    <div class="card-body">
                        @if($actors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Address</th>
                                            <th>Gender</th>
                                            <th>Height</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($actors as $actor)
                                            <tr>
                                                <td>{{ $actor->first_name ?? 'N/A' }}</td>
                                                <td>{{ $actor->address ?? 'N/A' }}</td>
                                                <td>{{ $actor->gender ?? 'N/A' }}</td>
                                                <td>{{ $actor->height ?? 'N/A' }}</td>
                                                <td>{{ $actor->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No actor submissions found.</p>
                                <a href="{{ route('actors.form') }}" class="btn btn-primary">Add First Actor</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
