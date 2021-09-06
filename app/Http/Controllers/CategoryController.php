<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::paginate(10);
        $filterKeyword = $request->get('name');

        if ($filterKeyword) {
            $categories = Category::where('name', 'LIKE', "%$filterKeyword%")->paginate(10);
        }

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'name' => 'required|min:3|max:20',
            'image' => 'required'
        ])->validate();

        $name = $request->get('name');
        $newCategory = new Category;
        $newCategory->name = $name;

        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $newCategory->image = $imagePath;
        }

        $newCategory->created_by = \Auth::user()->id;
        $newCategory->slug = \Str::slug($name, '-');
        $newCategory->save();
        return redirect()->route('categories.create')->with('status', 'Category successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->get('name');
        $slug = $request->get('slug');
        $category = Category::findOrFail($id);

        \Validator::make($request->all(), [
            'name' => 'required|min:3|max:20',
            'image' => 'required',
            'slug' => [
                'required',
                Rule::unique('categories')->ignore($category->slug, 'slug')
            ]    
        ])->validate();

        $category->name = $name;
        $category->slug = $slug;

        if ($request->file('image')) {
            if ($category->image && file_exists(storage_path('app/public' . $category->image))) {
                \Storage::delete('public/' . $category->image);
            }

            $newImage = $request->file('image')->store('category_images', 'public');
            $category->image = $newImage;
        }

        $category->updated_by = \Auth::user()->id;
        $category->slug = \Str::slug($name);
        $category->save();
        return redirect()->route('categories.edit', $id)->with('status', 'Category successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('status', 'Category successfully moved to trash.');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(10);
        return view('categories.trash', ['categories' => $categories]);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) {
            $category->restore();
        } else {
            return redirect()->route('categories.trash')->with('status', 'Category is not in the trash.');
        }

        return redirect()->route('categories.index')->with('status', 'Category successfully restored.');
    }

    public function deletePermanent($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        if (!$category->trashed()) {
            return redirect()->route('categories.trash')->with('status', 'Can not delete active category permanently.');
        } else {
            $category->forceDelete();
            return redirect()->route('categories.trash')->with('status', 'Category permanently deleted.');
        }
    }

    public function ajaxSearch(Request $request) {
        $keyword = $request->get('q');
        $categories = Category::where('name', 'LIKE', "%$keyword%")->get();
        return $categories;
    }
}
