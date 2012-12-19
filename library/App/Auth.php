<?php

/*
 * Login Users
 */

class App_Auth extends Zend_Auth_Adapter_DbTable {

    /**
     * Auth
     * @var Zend_Auth
     */
    private $auth;

    public function __construct() {

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        parent::__construct($dbAdapter);

        $this->setTableName('users')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('MD5(?)');

        $this->auth = Zend_Auth::getInstance();
    }

    /**
     * Verify user is logged
     * @return boolean
     */
    public function hasIdentity() {
        return $this->auth->hasIdentity();
    }

    /**
     * Login user
     * @param type $username
     * @param type $password
     * @return boolean
     */
    public function login($username, $password) {

        $this->setIdentity($username)->setCredential($password);

        $result = $this->authenticate();

        // Verify if login success
        if ($result->isValid()) {

            // Get data users except column password
            $data = $this->getResultRowObject(null, 'password');

            // Record in session
            $this->auth->getStorage()->write($data);

            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Object data user auth
     * @return Zend_Auth_Storage_Interface
     */
    public function getData()
    {
        return $this->auth->getStorage()->read();
    }

    /**
     * Logout session
     */
    public function logout() {
        $this->auth->clearIdentity();
    }

}

?>
