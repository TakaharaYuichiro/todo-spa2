<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Exception;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $items = Category::all();
    return response()->json([
      'data' => $items
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\CategoryRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CategoryRequest $request)
  {
    try {
      $item = Category::create($request->all());
      return response()->json([
        'data' => $item
      ], 201);
    } catch (Exception $err) {
      return response()->json([
        'error' => $err,
      ], 400);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function show(Category $category)
  {
    $item = Category::find($category);
    if ($item) {
      return response()->json([
        'data' => $item
      ], 200);
    } else {
      return response()->json([
        'message' => 'Not found',
      ], 404);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\CategoryRequest  $request
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function update(CategoryRequest $request, Category $category)
  {
    $update = [
      'name' => $request->name,
    ];

    try {
      $item = Category::where('id', $category->id)->update($update);
      if ($item) {
        return response()->json([
          'message' => 'Updated successfully',
        ], 200);
      } else {
        return response()->json([
          'error' => 'Not found',
        ], 404);
      }
    } catch (Exception $err) {
      return response()->json([
        'error' => $err,
      ], 400);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    try {
      $item = Category::where('id', $category->id)->delete();
      if ($item) {
        return response()->json([
          'message' => 'Deleted successfully',
        ], 200);
      } else {
        return response()->json([
          'message' => 'Not found',
        ], 404);
      }
    } catch (Exception $err) {
      return response()->json([
        'error' => $err,
      ], 400);
    }
  }

  public function checkValidation(CategoryRequest $request)
  {
    return response()->json([
      'message' => 'Validation OK'
    ], 200);
  }

  public function checkCategoryExists($id)
  {
    $categoryId = $id;
    $category = Category::find($categoryId);
    if ($category) {
      return response()->json([
        'message' => 'Exist Category'
      ], 200);
    } else {
      return response()->json([
        'error' => 'Not Exist Category'
      ], 201);
    }
  }
}
