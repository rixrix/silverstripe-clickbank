<?php
/**
 * ClickBank config file
 * 
 * @package		silverstripe-clickbank
 * @author		Richard Sentino <rix@mindginative.com>
 */

// Intercept ClickBank request and forward all request to ClickBank_Controller
//	e.g.
//		- http://www.domain.com/clickbank
Director::addRules(100, array (
		'clickbank' => 'ClickBank_Controller'
	)
);