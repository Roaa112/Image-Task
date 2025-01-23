$(document).on('click', '.delete-album-btn', function() {
    let albumId = $(this).data('id');
    $('#movePicturesAlbumId').val(albumId);
});

// Handle moving pictures to another album
$('#movePicturesBtn').on('click', function() {
    $('#deleteAlbumModal').modal('hide');
    $('#chooseDestinationAlbumModal').modal('show');
});

// Handle deleting pictures
$('#deletePicturesBtn').on('click', function() {
    let albumId = $('#movePicturesAlbumId').val();
    // Make an AJAX call to delete the pictures (backend logic needed)
    $.ajax({
        url: '/albums/' + albumId + '/delete-pictures',
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Add CSRF token
        },
        success: function(response) {
            alert(response.message); // Show success message
            window.location.reload(); // Reload the page
        },
        error: function() {
            alert('An error occurred while deleting the pictures.');
        }
    });
});

// Handle moving pictures form submission
$('#movePicturesForm').on('submit', function(e) {
    e.preventDefault();
    let albumId = $('#movePicturesAlbumId').val();
    let destinationAlbumId = $('#destination_album').val();
    
    // Make an AJAX call to move the pictures to the destination album (backend logic needed)
    $.ajax({
        url: '/albums/' + albumId + '/move-pictures',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            destination_album: destinationAlbumId
        },
        success: function(response) {
            alert(response.message); // Show success message
            window.location.reload(); // Reload the page
        }
    });
});