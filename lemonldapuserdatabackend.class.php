<?php

/**
 * LemonLDAP plaintext user data.
 *
 * Inspired from CAS authentication by Andreas Gohr <andi@splitbrain.org>, 
 *                                     Christopher Smith <chris@jalakai.co.uk>
 *                                 and Cedric Puig <cedric.puig@wanadoo.fr>
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Erwan Le Gall elegall@linagora.com
 * @date 20/12/06
 */

require_once(DOKU_AUTH.'/plain.class.php');

/**
 * This is a sample of userDataBackend.
 * LemonLDAP provides authentication, but not a userDataBackend.
 * Therefore, every one has his own implementation.
 * You just have to change this class to make it
 * working with your own userBackend.
 *
 * This implementation uses auth_plain,
 * wich works with a plain file.
 */
class lemonldapUserDataBackend {

  var $plainBackend = null;

  public function lemonldapUserDataBackend() {
    $this->plainBackend = new auth_plain();
    foreach($this->plainBackend->cando as $key => $value)
      $this->cando[$key] = $value;
  }

  public function getUserData($user) {
    return $this->plainBackend->getUserData($user);
  }

  public function getUserCount($filter=array()) {
    return $this->plainBackend->getUserCount($filter);
  }

  public function retrieveUsers($start=0, $limit=0, $filter=array()) {
    return $this->plainBackend->retrieveUsers($start, $limit, $filter);
  }

  public function createUser($user,$pass,$name,$mail,$grps=null){
    return $this->plainBackend->createUser($user,
                                           $pass,$name,$mail,$grps);
  }

  public function modifyUser($user, $changes) {
    return $this->plainBackend->modifyUser($user, $changes);
  }

  public function deleteUsers($users) {
    return $this->plainBackend->deleteUsers($users);
  }
}
