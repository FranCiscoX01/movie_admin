<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Film;
use App\CategoryMovie;
use App\PivotCategoryFilm;
use App\Picture;
use DB;
use Excel;

class FilmController extends Controller
{
    public function index()
    {
        $categories = CategoryMovie::where('status','!=',3)->get();

        return view('films.index', compact('categories'));
    }

    public function create()
    {
        $categories = CategoryMovie::where('status','!=',3)->get();

        return view('films.create', compact('categories'));
    }

    public function getFilms()
    {
        $data = DB::table('films as f')
                        ->join('pictures as p','p.id','=','f.picture_id')
                        ->where('f.status','!=',3)
                        ->where('p.status','!=',3)
                        ->select('f.*', 'p.url')
                        ->paginate(10);

        return $data;
    }
    
    public function store(Request $request)
    {
        $film = Film::create([
            'name' => $request['data']['name'],
            'description' => $request['data']['description'],
            'release_date' => $request['data']['release_date'],
            'director' => $request['data']['director'],
            'picture_id' => $request['data']['picture']['id'],
            'status' => 1,
        ]);
        if (!empty($film)) {
            foreach ($request['data']['categories_film'] as $categ) {
                $aux_category = explode('|', $categ);
                PivotCategoryFilm::create([
                    'film_id' => $film->id,
                    'category_id' => $aux_category[0],
                    'status' => 1,
                ]);
            }
            return response(['success' => 'Se creo el registro exitosamente'],200)->header('Content-Type', 'application/json');
        } else {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $film = Film::find($id);
            if (!empty($film)) {
                $film->status = $request['newStatus'];
                $film->save();
                return response(['success' => 'Se actualizo el registro exitosamente'],200)->header('Content-Type', 'application/json');
            } else {
                return response(['error' => 'NO se encontro pelicula'],200)->header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }

    public function getAllInfo($id)
    {
        $data = Film::find($id);

        $data->categories = DB::table('pivot_category_film as pcf')
                                    ->join('category_movies as cm','cm.id','=','pcf.category_id')
                                    ->where('pcf.film_id',$id)
                                    ->get();


        $data->picture_url = Picture::where('id',$data->picture_id)->select('url')->first();

        return $data;
    }

    public function update(Request $request)
    {
        try {
            $film = Film::find($request['data']['id']);
            if (!empty($film)) {
                $film->name = $request['data']['name'];
                $film->description = $request['data']['description'];
                $film->release_date = $request['data']['release_date'];
                $film->director = $request['data']['director'];
                $film->picture_id = $request['newImage']['id'];
                $film->save();
                
                $category_delete = PivotCategoryFilm::where('film_id',$request['data']['id'])->delete();
                if (count($request['categories']) > 0) {
                    foreach ($request['categories'] as $category) {
                        $aux_category = explode('|',$category);
                        PivotCategoryFilm::create([
                            'film_id' => $request['data']['id'],
                            'category_id' => $aux_category[0],
                            'status' => 1,
                        ]);
                    }
                }
                return response(['success' => 'Se actualizo el registro exitosamente'],200)->header('Content-Type', 'application/json');
            } else {
                return response(['error' => 'NO se encontro pelicula'],200)->header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }

    public function uploadFilmExcel(Request $request)
    {
        //Nombre por default
        $name = 'lista_peliculas';
        //Objeto de imagen
        $file = $request->file('file');
        //Extension de la imagen
        $extension = $file->getClientOriginalExtension();
        //Uid
        $uid = $request['dataFile'];
        //Nombre a guardar
        $fileName = $name.'.' . $extension;
        //Path donde se guardara provisionalmente
        Storage::disk('local')->put($fileName,  File::get($file));

        return $request;
    }

    public function removeFilmExcel(Request $request)
    {
        //Nombre por default
        $name = 'lista_peliculas';
        //nombre con el que se guarda
        $filename = $request['data']['uid'];
        //extencion del archivo
        $aux_extencion = explode('.', $request['data']['name']);
        $extencion = $aux_extencion[1];
        //archivo completo
        $delete_filename = $name.'.'.$extencion;

        Storage::disk('local')->delete($delete_filename);
        
        return $request;
    }

    public function uploadDataExcel()
    {
        $collection = Excel::toCollection(new Film, 'lista_peliculas.xlsx');
        $total = 0;
        
        foreach ($collection[0] as $c) {
            if ($total > 0) {

                $unix_date = ($c[1] - 25569) * 86400;
                $real_date = gmdate("Y-m-d", $unix_date);

                $exist_film = Film::where('name', $c[0])->first();
                if (empty($exist_film)) {

                    $film = Film::create([
                        'name' => $c[0],
                        'description' => $c[5],
                        'release_date' => $real_date,
                        'director' => $c[2],
                        'picture_id' => $c[4],
                        'status' => 1,
                    ]);

                    $aux_category = explode(',', $c[3]);
                    foreach ($aux_category as $categ) {
                        $name_category = CategoryMovie::where('name', $categ)->first();
                        if (!empty($name_category)) {
                            $pivot_categ_film = PivotCategoryFilm::create([
                                'film_id' => $film->id,
                                'category_id' => $name_category->id,
                                'status' => 1,
                            ]);
                        }
                    }

                }
            }
            $total++;
        }
    }
}
