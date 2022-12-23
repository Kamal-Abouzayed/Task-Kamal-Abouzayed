<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\MasterApiRequest;

class PostRequest extends MasterApiRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'image'  => 'required|image|mimes:png,jpg,jpeg',
            'pinned' => 'required',
            'tag_id' => 'required',
        ];
    }
}
