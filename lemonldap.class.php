<?php

/**
 * LemonLDAP authentication backend
 * Inspired from CAS authentication by Andreas Gohr <andi@splitbrain.org>, 
 *                                     Christopher Smith <chris@jalakai.co.uk>
 *                                 and Cedric Puig <cedric.puig@wanadoo.fr>
 * LemonLDAP only provides authentication mechanism
 * User data mechanism must be provided in an other module (lemonldapsuserdatabackend.class.php)
 * At this time only plain text mechanism is provided
 *
 *
 * The LemonLDAP server returns a username, and then auth_lemonldap uses
 * the userDataBackend to match this username with his
 * informations.
 *
 * Thanks to Thomas Chemineau tchemineau@linagora.com
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Erwan Le Gall elegall@linagora.com
 * @date 16/07/08
 */

define('DOKU_AUTH', dirname(__FILE__));

require_once(DOKU_AUTH.'/lemonldapuserdatabackend.class.php');
require_once(DOKU_AUTH.'/basic.class.php');

class auth_lemonldap extends auth_basic {

  var $lemon = null;
  var $userDataBackend = null;

  public function auth_lemonldap() {
    global $conf;
 
    $this->userDataBackend = new lemonldapUserDataBackend();
    foreach($this->userDataBackend->cando as $key => $value)
      $this->cando[$key] = $value;
    $this->cando['external'] = true;
    $this->cando['logoff'] = true;
  }

  public function logOff(){
    setcookie(DokuWiki, 'FALSE', time() - 600000, '/');
    // Head the Lemon Logout page
    $location = array();
    if ( preg_match("#https?://[^/]*#", $_SERVER["HTTP_REFERER"], $location)) {
    	header('Location: '.$location[0].'/logout');        
    } else {
        nice_die("Disconnection failed");
    }
  }

  public function trustExternal($user,$pass,$sticky=false){
    global $USERINFO;

    $username = $_SERVER{HTTP_REMOTE_USER};
    $USERINFO = $this->userDataBackend->getUserData($username);
    $success = $USERINFO !== false;
    if ($success) {
      $_SERVER['REMOTE_USER'] = $username;
      $_SESSION[DOKU_COOKIE]['auth']['user'] = $username;
      $_SESSION[DOKU_COOKIE]['auth']['info'] = $USERINFO;
    }
    return $success;
  }
 
  public function getUserData($user) {
    return $this->userDataBackend->getUserData($user);
  }
 
  public function getUserCount($filter=array()) {
    return $this->userDataBackend->getUserCount($filter);
  }
 
  public function retrieveUsers($start=0, $limit=-1, $filter=array()) {
    return $this->userDataBackend->retrieveUsers($start, $limit, $filter);
  }

  public function createUser($user, $pass, $name, $mail, $grps=null) {
    return $this->userDataBackend->createUser($user,$pass,$name,$mail,$grps);
  }
 
  public function modifyUser($user, $changes) {
    return $this->userDataBackend->modifyUser($user, $changes);
  }
 
  public function deleteUsers($users) {
    return $this->userDataBackend->deleteUsers($users);
  }


}
