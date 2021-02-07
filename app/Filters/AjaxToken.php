<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AjaxToken implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		// Do something here
	}
	
	//--------------------------------------------------------------------
	
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		$feedback = (object)[];
		
		$arguments = $request->getHeaderLine('Authorization');
		$arguments = explode('Bearer ', $arguments)[1];
		
		if ($arguments) {
			
			$tokenModel = new \App\Models\Authentication\TokenModel();
			$tokenFeedback = $tokenModel->tokenControl($arguments);
			
			if ($tokenFeedback->status === 'error') {
				
				$feedback = $tokenFeedback;
				
				return $response->setStatusCode(205)->setJSON($feedback);
			}
		} else {
			$feedback->status = 'error';
			$feedback->error_code = 'token';
			$feedback->code = 'TokenNotFound';
			$feedback->message = 'Oturumunuzun süresi dolmuştur.';
			
			return $response->setStatusCode(401)->setJSON($feedback);
		}
		
	}
}