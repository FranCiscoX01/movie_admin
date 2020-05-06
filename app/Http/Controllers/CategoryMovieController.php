<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryMovie;

class CategoryMovieController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function getCategories()
    {
        $data = CategoryMovie::where('status','!=',3)->orderBy('id', 'DESC')->distinct()->paginate(10);

        return $data;
    }

    public function store(Request $request)
    {
        $data = CategoryMovie::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'status' => 1,
        ]);
        if (!empty($data)) {
            return response(['success' => 'Se creo el registro exitosamente'],200)->header('Content-Type', 'application/json');
        } else {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $category = CategoryMovie::find($id);
            if (!empty($category)) {
                $category->status = $request['newStatus'];
                $category->save();
                return response(['success' => 'Se actualizo el registro exitosamente'],200)->header('Content-Type', 'application/json');
            } else {
                return response(['error' => 'NO se encontro categoria'],200)->header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = CategoryMovie::find($id);
            if (!empty($category)) {
                $category->name = $request['data']['name'];
                $category->description = $request['data']['description'];
                $category->save();
                return response(['success' => 'Se actualizo el registro exitosamente'],200)->header('Content-Type', 'application/json');
            } else {
                return response(['error' => 'NO se encontro categoria'],200)->header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            return response(['error' => 'Hubo un error en el servidor'],200)->header('Content-Type', 'application/json');
        }
    }
}
