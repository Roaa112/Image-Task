<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
  

    public function index()
    {
        $albums = Album::where('user_id', auth()->id())->get();
        return view('dashboard', compact('albums'));
    }

   
    public function store(Request $request)
    {
       
        $request->validate(['name' => 'required|string|max:255']);
        $album = auth()->user()->albums()->create($request->all());
        return redirect()->route('albums.index');
    }


    
    public function show(Album $album)
{
    // تحميل الصور المرتبطة بالألبوم
    $photos = $album->photos;

    return view('albums.show', compact('album', 'photos'));
}


    public function edit(Album $album)
    {
        $this->authorize('update', $album);
        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
       
        $request->validate(['name' => 'required|string|max:255']);
        $album->update($request->all());
        return redirect()->route('albums.index');
    }


    public function deletePictures($albumId)
    {
        $album = Album::findOrFail($albumId);

        
        if ($album->hasMedia('photos')) {
          
            $album->clearMediaCollection('photos');
        }

       
        $album->delete();

        return response()->json(['message' => 'Album and all pictures have been deleted.']);
    }



    public function movePictures(Request $request, $albumId)
    {
        
        $album = Album::findOrFail($albumId);

      
        $destinationAlbum = Album::findOrFail($request->destination_album);

        
        if ($album->hasMedia('photos')) {
           
            foreach ($album->getMedia('photos') as $media) {
                $destinationAlbum->addMedia($media->getPath())->toMediaCollection('photos');
            }

            
            $album->clearMediaCollection('photos');

          
            $album->delete();

            return response()->json(['message' => 'Pictures have been moved to the new album and the original album has been deleted.']);
        }

        return response()->json(['message' => 'No pictures to move.'], 404);
    }

}

