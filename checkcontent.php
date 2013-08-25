<?php 
// *********************************************************
//
// Title: Register page callback
// Usage : search for an account in database and send info back to caller
// parameters : 
// GET 'cmd': command must be 's' for search
// GET 'u': user login to search for
//
// returns: true if found, false if not found
// XML structure with result as <COUNT>TRUE|FALSE</COUNT>
// Version: 1.0.0
//
// Author: TG
//
// *********************************************************

// global init variables and functions
include 'utils.php';
// includes autoload function to search for objects in the Library path automatically without having to include them in lists
include 'autoload-ajax.php';

// instantiate user object to have global variable access
if (!isset($user)) $user = new \Library\User;
// instantiate http header request information
$httpRequest = new \Library\HTTPRequest;


// loads Database access objects
if (!isset($managers)) $managers = new \Library\Managers('PDO', \Library\PDOFactory::getMysqlConnexion());
// Loads Connection object to access to user database routines
$obox = $managers->getManagerOf('oBox');

// get parameters then
// process commands from URI
$cmd = $httpRequest->getData('cmd');
if (!isset($cmd)) die('no command sent, abort');

$log = new \Library\Logging();

// build XML header answer
header('Content-Type: application/json');

switch ($cmd) {
	case 's': // search if userid already exist
                $start = $httpRequest->getData('start');
		$s = ($obox->searchNewContent($start)>0 ? true:false);
                $str = json_encode(array(array('update'=>$s)));
                $log->lwrite("wallapi cmd=s ret=".($s?'true':'false'));
		break;
        case 'w': // wall view 
            	$uid=$user->getAttribute('uid');
                $log->lwrite("wallapi b2b called sort=".$httpRequest->getData('sort')." priv=".$httpRequest->getData('private')." search=".$httpRequest->getData('search')." page=".$httpRequest->getData('page'));
        	$slist = $obox->getB2BWalloBoxDetails($httpRequest->getData('sort'),$httpRequest->getData('private'),$uid,$httpRequest->getData('search'),$httpRequest->getData('page'));
                $log->lwrite("wallapi cmd=w ret=".(count($slist)>0?count($slist):0));
                $str = json_encode($slist);
                break;
        case 'sp': // wall view 
            	$uid=$user->getAttribute('uid');
                $log->lwrite("wallapi b2b called sort=".$httpRequest->getData('sort')." priv=".$httpRequest->getData('private')." search=".$httpRequest->getData('search')." page=".$httpRequest->getData('page'));
        	$slist = $obox->getWalloBoxDetails($httpRequest->getData('sort'),$httpRequest->getData('private'),$uid,$httpRequest->getData('search'),$httpRequest->getData('page'));
                $log->lwrite("wallapi cmd=sp ret=".(count($slist)>0?count($slist):0));
                $str = json_encode($slist);
                break;
	default: 
		break;
};
echo $str;
?>