<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // authorize()メソッドはtrueまたはfalseを返す必要があり、trueを返せばそのまま処理が進行し、rules()メソッドに設定したバリデーションが行われます。一方でfalseを返した場合、Laravel側で自動的にHTTPステータスコードの403（閲覧禁止）を返し、HTTPリクエストを拒否します。
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required'
        ];
    }
}
