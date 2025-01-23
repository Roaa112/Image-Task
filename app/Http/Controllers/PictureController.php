<?php

namespace App\Http\Controllers;


use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PictureController extends Controller
{
  
  
   
    public function store(Request $request)
    {
        $albumId = $request->input('albumId');
    
        // Validate request
        $request->validate([
            'photos' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);
    
        try {
            $album = Album::findOrFail($albumId);
            
            // Single file upload
            $photo = $request->file('photos');
            
            $uploadedImage = $album->addMedia($photo)
                                   ->toMediaCollection('photos');
            
            return response()->json([
                'image_urls' => [$uploadedImage->getUrl()]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
   
   // delete image inside the album (updating)
    public function delete($albumId, $mediaId)
    {
        $album = Album::find($albumId);
    
        if (!$album) {
            return response()->json(['success' => false, 'message' => 'Album not found.'], 404);
        }
    
        $media = $album->getMedia('photos')->where('id', $mediaId)->first();
    
        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Photo not found.'], 404);
        }
    
        $media->delete();
    
        return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
    }
    
}

