<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class ErrorPage extends BaseController {

	/**


	 */
	public function index()
	{
		echo view('pages/error');		
		
	}

}