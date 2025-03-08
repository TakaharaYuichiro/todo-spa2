<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CategoryRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => ['required', 'unique:categories', 'string', 'max:191']
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'カテゴリを入力してください',
      'name.unique' => 'カテゴリが既に存在しています',
      'name.string' => 'カテゴリを文字列で入力してください',
      'name.max' => 'カテゴリを191文字以下で入力してください',
    ];
  }

  protected function failedValidation(Validator $validator)
  {
    $response['errors']  = $validator->errors()->toArray();

    throw new HttpResponseException(response()->json($response));
  }
}
