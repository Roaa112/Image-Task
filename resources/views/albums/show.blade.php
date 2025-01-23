@extends('User.layout')

@section('body')
<div class="container">
    <br><br><br>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pictures.store') }}" 
          method="POST" 
          class="dropzone" 
          id="image-upload-form"
          enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="albumId" value="{{ $album->id }}">
        <div class="dz-message" id="Drop">
            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
            <p class="mt-2">اسحب الصور هنا أو اضغط للتحميل</p>
        </div>

        <div class="form-actions">
    <a href="{{ route('albums.index') }}" class="btn btn-warning mr-1">
        <i class="fas fa-arrow-left"></i> رجوع
    </a>
</div>

    </form>

    <br><br>

    <div class="row mt-4" id="image-gallery">
        @foreach($album->getMedia('photos') as $photo)
            <div class="col-md-3 mb-3" id="image-{{ $photo->id }}">
                <div class="card">
                    <img src="{{ $photo->getUrl() }}" class="card-img-top" alt="Photo">
                </div>
                <p class="image-name">{{ $photo->file_name }}</p>
                <button class="btn btn-danger delete-photo" 
                        data-photo-id="{{ $photo->id }}" 
                        data-album-id="{{ $album->id }}">
                    حذف
                </button>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<style>
    .dropzone {
        border: 2px dashed #007bff;
        background: #f8f9fa;
        padding: 40px;
        text-align: center;
        cursor: pointer;
    }
    .dz-message {
        font-size: 1.2rem;
        font-weight: bold;
    }
    .card img {
        height: 200px;
        object-fit: cover;
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
 // Updated Dropzone Configuration
Dropzone.options.imageUploadForm = {
    url: "{{ route('pictures.store') }}",
    paramName: "photos",
    maxFilesize: 5,
    acceptedFiles: "image/*",
    autoProcessQueue: true,
    uploadMultiple: false,  // Simplified to single file upload
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
        albumId: "{{ $album->id }}"
    },
    success: function(file, response) {
        if (response.image_urls) {
            let gallery = document.getElementById("image-gallery");
            response.image_urls.forEach(url => {
                let imgCard = `
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="${url}" class="card-img-top" alt="Uploaded Image">
                        </div>
                    </div>`;
                gallery.innerHTML += imgCard;
            });
        }
    },
    error: function(file, errorMessage) {
        console.error("Upload error:", errorMessage);
        alert(errorMessage);
    }
};

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-photo").forEach(button => {
            button.addEventListener("click", function () {
                let photoId = this.dataset.photoId;
                let albumId = this.dataset.albumId;
                let photoContainer = document.getElementById(`image-${photoId}`);

                if (confirm("هل أنت متأكد من حذف هذه الصورة؟")) {
                    fetch(`/albums/${albumId}/photos/${photoId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            photoContainer.remove();
                        } else {
                            alert("حدث خطأ أثناء حذف الصورة!");
                        }
                    })
                    .catch(error => console.error("Error deleting photo:", error));
                }
            });
        });
    });
</script>
@endsection