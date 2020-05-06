<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Picture;

class PictureController extends Controller
{
    public function uploadPictureFilm(Request $request)
    {
        //Tamaño de la imagen
        $widthPicture = $request['widthPicture'];
        $heightPicture = $request['heightPicture'];
        //Seccion de la imagen
        $picture_section = $request['picture_section'];
        //Objeto de imagen
        $file = $request->file('file');
        //Extension de la imagen
        $extension = $file->getClientOriginalExtension();
        //Uid
        $uid = $request['dataFile'];
        //Folder
        $folder = $request['folder'];
        //Nombre a guardar
        $fileName = $uid.'.' . $extension;
        //Path donde se guardara provisionalmente
        Storage::disk('public')->put($folder.'/'.$fileName,  File::get($file));
        //Insertar en la base de datos
        $picture = $this->DBPicture($fileName,$uid);

        return $picture;
    }
    
    private function DBPicture($name,$uid)
    {
        $db_picture = Picture::create([
            'url' => $name,
            'slug' => $uid,
            'description' => $uid,
            'status' => 1,
        ]);

        return $db_picture;
    }

    public function removePictureFilm(Request $request)
    {
        $slug = Picture::where('slug',$request['slug'])->first();

        if (!empty($slug)) {
            $name = $slug->name;
            $slug_delete = Picture::where('id',$slug->id)->delete();
            Storage::disk('public')->delete($request['folder'].'/'.$name);
        }

        return $slug;
    }
}
