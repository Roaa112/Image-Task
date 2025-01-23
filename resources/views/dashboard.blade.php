@extends('User.layout')

@section('body')
<div class="container">
   <br><br><br>

    <!-- Display Albums as Folders -->
    <div class="row">
        @foreach($albums as $album)
            <div class="col-md-4 text-center">
                <a href="{{ route('albums.show', $album->id) }}" class="folder">
                    <div class="folder-container">
                        <div class="folder-back"></div>
                        <div class="folder-front">
                        @if($album->hasMedia('photos'))
                            <div class="album-images">
                                @foreach($album->getMedia('photos') as $media)
                                    <img src="{{ $media->getUrl() }}" alt="Album Image" class="album-thumbnail">
                                @endforeach
                            </div>
                        @else
                        <img src="{{ asset('User/assets/images/folder.png') }}" alt="logo" style="width: 100px; height: auto;">
                        @endif
                        </div>
                    </div>
                </a> 
                <p class="folder-name">{{ $album->name }}</p>

                <!-- Edit Button -->
                <button class="btn btn-primary mt-2 edit-album-btn" 
                        data-id="{{ $album->id }}" 
                        data-name="{{ $album->name }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#editAlbumModal">
                    Edit Album
                </button>

                <!-- Delete Button -->
                <button class="btn btn-danger mt-2 delete-album-btn" 
                        data-id="{{ $album->id }}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteAlbumModal">
                    Delete Album
                </button>
            </div>
        @endforeach
    </div>

    <!-- Modal to Edit Album -->
    <div class="modal fade" id="editAlbumModal" tabindex="-1" aria-labelledby="editAlbumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAlbumModalLabel">Edit Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAlbumForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="album_id" id="editAlbumId">
                        <div class="mb-3">
                            <label for="albumName" class="form-label">Album Name</label>
                            <input type="text" class="form-control" id="albumName" name="name" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to Delete Album -->
    <div class="modal fade" id="deleteAlbumModal" tabindex="-1" aria-labelledby="deleteAlbumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAlbumModalLabel">Delete Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this album? This will delete or move all the pictures within it.</p>
                    
                    <!-- Confirmation Choices -->
                    <button type="button" class="btn btn-warning" id="movePicturesBtn">Move Pictures to Another Album</button>
                    <button type="button" class="btn btn-danger" id="deletePicturesBtn">Delete All Pictures</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to Choose Destination Album -->
    <div class="modal fade" id="chooseDestinationAlbumModal" tabindex="-1" aria-labelledby="chooseDestinationAlbumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseDestinationAlbumModalLabel">Choose Destination Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="movePicturesForm">
                        <div class="mb-3">
                            <label for="destination_album" class="form-label">Destination Album</label>
                            <select class="form-control" id="destination_album" name="destination_album" required>
                                @foreach($albums as $album)
                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Move Pictures</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@section('js')
<script>
// When edit album button is clicked
$(document).on('click', '.edit-album-btn', function() {
    let albumId = $(this).data('id');
    let albumName = $(this).data('name');

    $('#editAlbumId').val(albumId);
    $('#albumName').val(albumName);
    
    // Set form action dynamically
    let formAction = '/albums/' + albumId;
    $('#editAlbumForm').attr('action', formAction);
});

// When delete album button is clicked
$(document).on('click', '.delete-album-btn', function() {
    let albumId = $(this).data('id');
    $('#deletePicturesBtn').data('id', albumId); // Store album ID for deletion
    $('#deleteAlbumModal').modal('show');
});

// When "Move Pictures" button is clicked
$('#movePicturesBtn').on('click', function() {
    $('#deleteAlbumModal').modal('hide');
    $('#chooseDestinationAlbumModal').modal('show');
});

// When "Delete Pictures" button is clicked
$('#deletePicturesBtn').on('click', function() {
    let albumId = $(this).data('id');
    $.ajax({
        url: '/albums/' + albumId + '/delete-pictures',
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert(response.message);
            window.location.reload(); 
        },
        error: function() {
            alert('An error occurred while deleting the pictures.');
        }
    });
});

// When moving pictures form is submitted
$('#movePicturesForm').on('submit', function(e) {
    e.preventDefault();
    let albumId = $('#deletePicturesBtn').data('id');
    let destinationAlbumId = $('#destination_album').val();
    
    $.ajax({
        url: '/albums/' + albumId + '/move-pictures',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            destination_album: destinationAlbumId
        },
        success: function(response) {
            alert(response.message);
            window.location.reload();
        },
        error: function() {
            alert('An error occurred while moving the pictures.');
        }
    });
});
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@endsection