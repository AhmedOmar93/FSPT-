<?php 

class ConnectionInfoController extends BaseController {

	public function createConnection ( ){
	//	die(var_dump((string)$this->get_ip_address()));
		 $data = array(
		 	'users'=> Auth::user()->id,
		 	'connId'=>Input::get('connId'),
		 	'conn_sess'=>Input::get('conn_sess'),
		 	'ip'=>(string)$this->get_ip_address(),
		 	'browser'=>Input::get('browser'),
		 	'device'=>Input::get('device'),
		 	'os'=>Input::get('os'),
		 	'os_version'=>Input::get('os_version'),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString()
        );
		 return Users::createConn($data);
	} 

	public function getConnInfo( ){
		return Users::getConnInfo();
	}

	public function getUserByConnId($id)
	{
		$result = Users::getUserByConnId($id);
		return $result;
	}

	public function CloseConnection($id)
	{
		return Users::CloseConnection($id);
	}

	public function getOnlineUsers(){
		$data =  Users::getOnlineUsers();
		$arrData = [];
        foreach ($data as $key => $value) {
            $tempData = [];
            foreach ($value as $key => $value) {
                    $tempData[$key] = $value;          
                } 
                $arrData[]= $tempData;        
        }
        $data = json_encode($arrData);
		return $data;
	}



	function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if ($this->validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }

    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}


/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}


}