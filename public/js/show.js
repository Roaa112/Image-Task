Dropzone.options.imageUploadForm = {
    paramName: "photos",  // The name of the file input field for Dropzone
    maxFilesize: 5,       // Maximum file size in MB
    acceptedFiles: "image/*",  // Accept only image files
    autoProcessQueue: false,   // Disable auto processing, the user will manually trigger the upload
    addRemoveLinks: true,      // Enable the remove links for the files
    dictDefaultMessage: "اسحب الصور هنا أو انقر للتحميل", // Custom message for Dropzone
    uploadMultiple: true,      // Allow multiple file uploads at once
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"  // CSRF token for security
    },
    
    // Initialization callback
    init: function () {
        var myDropzone = this;

        // Handle submit button click event to start the upload process
        document.querySelector("form button[type='submit']").addEventListener("click", function (e) {
            e.preventDefault();
            if (myDropzone.files.length > 0) {
                // If files are selected, process the queue and upload the files
                myDropzone.processQueue();
            } else {
                alert('من فضلك، قم بإضافة صور أولاً.');
            }
        });

        // Success event: handle the response and update the gallery
        this.on("success", function (file, response) {
            console.log("Upload successful, response:", response);  // Debugging response

            // Check if the response contains image URLs
            if (response.image_urls) {
                let gallery = document.getElementById("image-gallery");
                // Add each uploaded image to the gallery
                response.image_urls.forEach(url => {
                    let imgCard = `
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="${url}" class="card-img-top" alt="Uploaded Image">
                            </div>
                        </div>`;
                    gallery.innerHTML += imgCard;
                });
            } else {
                console.error("⚠️ خطأ: لم يتم استقبال رابط الصورة!");
            }
        });

        // Error event: log any upload errors
        this.on("error", function (file, errorMessage) {
            console.error("❌ خطأ في الرفع:", errorMessage);
        });
    }
};

// Event listener for deleting photos
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-photo").forEach(button => {
        button.addEventListener("click", function () {
            let photoId = this.dataset.photoId;
            let albumId = this.dataset.albumId;
            let photoContainer = document.getElementById(`image-${photoId}`);

            // Confirm deletion with the user
            if (confirm("هل أنت متأكد من حذف هذه الصورة؟")) {
                // Send DELETE request to the backend to delete the photo
                fetch(`/albums/${albumId}/photos/${photoId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",  // CSRF token
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // If successful, remove the photo from the gallery
                        photoContainer.remove();
                    } else {
                        alert("حدث خطأ أثناء حذف الصورة!");
                    }
                })
                .catch(error => console.error("خطأ في الحذف:", error));
            }
        });
    });
});
