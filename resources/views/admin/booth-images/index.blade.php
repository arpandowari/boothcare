@extends('layouts.app')

@section('title', 'Manage Booth Images')

@section('content')
<div class="container-fluid">
    <h1>Manage Booth Images</h1>
    <p>Upload and manage images for booth displays on the public website</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @foreach($booths as $booth)
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $booth->booth_name }} ({{ $booth->area->area_name ?? 'N/A' }})</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $booth->id }}">
                    Add Image
                </button>
            </div>
            <div class="card-body">
                @if($booth->images->count() > 0)
                    <div class="row">
                        @foreach($booth->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ $image->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6>{{ $image->title ?? 'No Title' }}</h6>
                                        <form action="{{ route('admin.booth-images.delete', $image) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No images uploaded yet.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $booth->id }}">
                        Upload First Image
                    </button>
                @endif
            </div>
        </div>

        <!-- Modal for {{ $booth->booth_name }} -->
        <div class="modal fade" id="modal{{ $booth->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Image - {{ $booth->booth_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.booth-images.store', $booth) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Image File</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title (Optional)</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload Image</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if($booths->count() === 0)
        <div class="alert alert-info">
            <h4>No booths found</h4>
            <p>Create booths first to manage their images.</p>
        </div>
    @endif
</div>
@endsection