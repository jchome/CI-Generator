<?php 

namespace App\Controllers;

/**
 * Renders a HTML content
 */
class HtmlController extends BaseController {


	protected function view($page, $data, $title) {
		if (! is_file(APPPATH . 'Views/' . $page . '.php')) {
			print("Cannot open view to ". $page);
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		if(! isset( $data['currentUser'] ) ){
			$data['currentUser'] = session()->get('currentUser');
		}
		echo view('header', [
			"menu" => $title,
			"locale" => $this->request->getLocale()
		]);
		echo view($page, $data);
		echo view('footer');
	}
}