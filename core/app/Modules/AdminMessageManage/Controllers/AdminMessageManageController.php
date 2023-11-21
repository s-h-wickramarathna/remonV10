<?php namespace App\Modules\AdminMessageManage\Controllers;


/**
* CONTROLLER
* @author Author <info.tharindumac@gmail.com>
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/


use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Core\PhpImap\Mailbox;
use Core\PhpImap\IncomingMail;
use Core\PhpImap\IncomingMailAttachment;

class AdminMessageManageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	
		
		return view("AdminMessageManage::index")->with(['messages'=>[]]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function composeView()
	{	
		

		return view("AdminMessageManage::compose");
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function readView($id)
	{
		return view("AdminMessageManage::read");
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
