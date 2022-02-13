<?php
declare(strict_types=1);

class LottoController extends ControllerBase
{

    public function getAction()
    {
 	    $this->view->disable();
	    $request = $this->request;
	    $response = new \Phalcon\Http\Response();
	    $response->_isJsonResponse = true;
	    $response->setContentType('application/json', 'UTF-8');
	    $response->setContent(json_encode('Error'));
	    if ($request->isPost() && $request->isAjax()) {
		    $lotto_numbers = $request->getPost('lotto_numbers');
		    print_r($lotto_numbers);
		    $lotto = new Lotto();
		    $return_obj = $lotto->getdata(json_decode($lotto_numbers));
		    $response->setContent(json_encode($return_obj));
	    }
	    return $response;
    }

}

