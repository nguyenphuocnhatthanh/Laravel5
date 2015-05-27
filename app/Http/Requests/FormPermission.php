<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormPermission extends Request {



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
        $name = 'required|unique:permissions,name';
        if($this->has('id')) {
            $name .= ','.$this->get('id');
        }

		return [
			'name'  => $name,
            'display_name'  => 'required'
		];
	}

}
