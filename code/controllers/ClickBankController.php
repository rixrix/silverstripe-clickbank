<?php
/**
 * ClickBank module controller
 * 
 * @package		silverstripe-clickbank
 * @subpackage	controllers
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBank_Controller extends Controller {
	
	static $url_handlers = array (
		'' => 'index',
		'ipn!' => 'ipn',
	);
	
	function index(SS_HTTPRequest $request) {
		return Director::redirect('/server-error');
	}
	
	/**
	 * Process IPN request from ClickBank. Only process POST request
	 * 
	 * @param	object	$_POST
	 * @return	int		HTTP code 
	 */
	public function ipn(SS_HTTPRequest $request) {
		if ($request->isPost()) {
			if (ClickBank::validate_ipn_request($request->postVars())) {
				ClickBank::process_ipn_request($request->postVars());
				return Director::get_status_code();
			}
		}
		return ErrorPage::response_for(404); 		
	}
}