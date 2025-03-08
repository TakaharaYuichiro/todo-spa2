<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\TodoRequest;
use Exception;

class TodoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $items = Todo::all();
    return response()->json([
      'data' => $items
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\TodoRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TodoRequest $request)
  {
    try {
      $item = Todo::create($request->all());
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
   * @param  \App\Models\Todo  $todo
   * @return \Illuminate\Http\Response
   */
  public function show(Todo $todo)
  {
    $item = Todo::find($todo);
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
   * @param  \Illuminate\Http\TodoRequest  $request
   * @param  \App\Models\Todo  $todo
   * @return \Illuminate\Http\Response
   */
  public function update(TodoRequest $request, Todo $todo)
  {
    $update = [
      'user_id' => $request->user_id,
      'category_id' => $request->category_id,
      'content' => $request->content,
    ];

    try {
      $item = Todo::where('id', $todo->id)->update($update);
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
   * @param  \App\Models\Todo  $todo
   * @return \Illuminate\Http\Response
   */
  public function destroy(Todo $todo)
  {
    try {
      $item = Todo::where('id', $todo->id)->delete();
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

  public function checkValidation(TodoRequest $request)
  {
    return response()->json([
      'message' => 'Validation OK'
    ], 200);
  }
}
