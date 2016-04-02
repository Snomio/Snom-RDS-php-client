<?php
# Copyright (C) 2013 snom technology <pietro.bertera@snom.com>
#
# This program is available under the Lesser General Public Licence (LGPL) Version 3
#
# This software is released for didactical and debugging purposes.
# You're free to use it at your own risk.
# You can modify and redistribute this program under the LGPLv3 license terms.
#
# This script uses a cURL wrapper class written by Dongsheng Cai (http://dongsheng.org)
#

include "curl.class.php";

$models = array("snom710", "snom720", "snom760", "snom300", "snom320", "snom360", "snom370", "snomMP", "snom820", "snom821", "snom870");

class xmlrpc_client {
    private $url;
    function __construct($url, $debug) {
        $this->url = $url;
        $this->connection = new curl(array("cache"=>false, "debug"=>$debug));
        $this->connection->set_headers("Content-Type: text/xml");
    }
    public function call($method, $params = null) {
        $post = xmlrpc_encode_request($method, $params, array("encoding"=>"utf-8"));
        return xmlrpc_decode($this->connection->post($this->url, $post));
    }
}

function deregister_phone($mac, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	$resp = $client->call('redirect.deregisterPhone', array($mac));
	return $resp;
}

function register_phone($mac, $url, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	$resp = $client->call('redirect.registerPhone', array($mac, $url));
	return $resp;
}

function register_phone_list($mac, $url, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	$resp = $client->call('redirect.registerPhoneList', array($mac, $url));
	return $resp;
}

function deregister_phone_list($mac, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	$resp = $client->call('redirect.deregisterPhoneList', array($mac));
	return $resp;
}

function check_phone($mac, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	$resp = $client->call('redirect.checkPhone', array($mac));
	return $resp;
}

function list_all($model, $url, $user, $pass){
	$server = "https://$user:$pass@provisioning.snom.com:8083/xmlrpc/";
	$client = new xmlrpc_client($server, false);
	if ($url == ""){
		$resp = $client->call('redirect.listPhones', array($model));
	} else {
		$resp = $client->call('redirect.listPhones', array($model, $url));
	}
	return $resp;
}


if (isset($_POST["username"])){
	$username = $_POST['username'];
} else {
	$username = "";
}
if (isset($_POST["password"])){
	$password = $_POST['password'];
} else {
	$password = "";
}

switch ($_POST['action']) {

	case "check_phone":
		$result = check_phone($_POST['check_mac'], $_POST['username'], $_POST['password']);
	break;

	case "register_phone":
		$result = register_phone($_POST['register_mac'], $_POST['register_url'], $_POST['username'], $_POST['password']);
	break;
	
	case "register_phone_list":
		$mac = explode(" ", $_POST['register_mac_list']);
		$result = register_phone_list($mac, $_POST['register_url_list'], $_POST['username'], $_POST['password']);
	break;

	case "deregister_phone_list":
		$mac = explode(" ", $_POST['deregister_mac_list']);
		$result = deregister_phone_list($mac, $_POST['username'], $_POST['password']);
	break;

	case "deregister_phone":
		$result = deregister_phone($_POST['deregister_mac'], $_POST['username'], $_POST['password']);
	break;

	case "list_all":
		$result = list_all($_POST['list_model'], $_POST['list_url'], $_POST['username'], $_POST['password']);
	break;
}
?>

<html>
  <head>
   <title>Snom redirection service test page</title>
   <link rel="stylesheet" href="css/styles.css" type="text/css" charset="utf-8"/>
  </head>
  <body>
    <table width="100%" height="100%" cellspacing="0">
      <tr>
        <td></td>
        <td class="middlecol">
         <h1>Snom redirection service test page</h1>
         <p>
         This page is a simple Snom redirection service client written in PHP.
         You can test the redirection sevice filling the following form:
         </p>
        <table><tr><td>
         <form enctype="multipart/form-data" method="post">
          <table cellspacing="0">
           <tr>
            <td colspan="2"><h3>Your credentials:</h3></td>
           </tr>
           <tr>
            <td>
             Username:
            </td>
            <td>
             <input name="username" type="text" value="<?php echo $username;?>"/>
            </td>
           </tr>

           <tr>
            <td>
             Password:
            </td>
            <td>
             <input name="password" type="password" value="<?php echo $password;?>"/>
            </td>
           </tr>
                     
           <tr>
            <td colspan="2"><h3>Check Phone:</h3></td>
           </tr>
           <tr>
            <td>Mac:</td><td><input name="check_mac" type="text" /></td>
           </tr>
 
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="check_phone" name="action">Check Phone</button>
            </td>
           </tr>

           <tr>
            <td colspan="2"><h3>Register Phone:</h3></td>
           </tr>
           <tr>
            <td>Mac:</td><td><input name="register_mac" type="text" /></td>
           </tr>
           <tr>
            <td>URL:</td><td><input name="register_url" type="text" /></td>
           </tr>
 
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="register_phone" name="action">Register Phone</button>
            </td>
           </tr>

           <tr>
            <td colspan="2"><h3>Deregister Phone:</h3></td>
           </tr>
           <tr>
            <td>Mac:</td><td><input name="deregister_mac" type="text" /></td>
           </tr>
 
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="deregister_phone" name="action">Deregister Phone</button>
            </td>
           </tr>

           <tr>
            <td colspan="2"><h3>List All:</h3></td>
           </tr>
           <tr>
            <td>Model:</td><td><select name="list_model"><?php foreach($models as $mod){ echo "<option value=$mod>$mod</option>";}; ?></select>
           </tr>
           <tr>
            <td>URL:</td><td><input name="list_url" type="text" /></td>
           </tr>
 
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="list_all" name="action">List All</button>
            </td>
           </tr>

           <tr>
            <td colspan="2"><h3>Register Phone List:</h3></td>
           </tr>
           <tr>
            <td>Mac:</td><td><input name="register_mac_list" type="text">Mac address separed by space</td>
           </tr>
           <tr>
            <td>URL:</td><td><input name="register_url_list" type="text" /></td>
           </tr>
 
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="register_phone_list" name="action">Register Phones</button>
            </td>
           </tr>

           <tr>
            <td colspan="2"><h3>De-register Phone List:</h3></td>
            
           </tr>
           <tr>
            <td>Mac:</td><td><input name="deregister_mac_list" type="text">Mac addresses separed by space</td>
           </tr>
           <tr>
            <td colspan="2" align="right">
             <button class="button" type="submit" value="deregister_phone_list" name="action">De-register Phones</button>
            </td>
           </tr>


           <tr>
            <td>
            </td>
            <td>
            </td>
           </tr>

          </table>
         </form> 
	</td><td class="result">
    
        <h2>Result:</h2> 
        <textarea readonly><?php print_r($result); ?></textarea>

       </td></table>

        </td>
        <td></td>
      </tr>
      <tr>
        <td height="40px"></td>
        <td> © 2013 snom technology  </td>
      </tr>
    </table>
  </body> 
</html>
