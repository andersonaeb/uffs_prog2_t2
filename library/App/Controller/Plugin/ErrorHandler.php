<?php

class App_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_ErrorHandler {
    /**
     * Page not found exception
     */

    const EXCEPTION_PAGE_NOT_FOUND = 'EXCEPTION_PAGE_NOT_FOUND';

    /**
     * No access exception
     */
    const EXCEPTION_NO_ACCESS = 'EXCEPTION_NO_ACCESS';

    /**
     * Maintenance exception
     */
    const EXCEPTION_MAINTENANCE = 'EXCEPTION_MAINTENANCE';

    // @todo add more EXCEPTION constant if needed to define exception type
    // const EXCEPTION_NAME = 'EXCEPTION_NAME';

    /**
     * Handle errors and exceptions
     *
     * If the 'noErrorHandler' front controller flag has been set,
     * returns early.
     *
     * @param $request Zend_Controller_Request_Abstract       	
     * @return void
     */
    protected function _handleError(Zend_Controller_Request_Abstract $request) {
        $frontController = Zend_Controller_Front::getInstance();
        $response = $this->getResponse();

        if ($this->_isInsideErrorHandlerLoop) {
            $exceptions = $response->getException();
            if (count($exceptions) > $this->_exceptionCountAtFirstEncounter) {
                // Exception thrown by error handler; tell the front controller
                // to throw it
                $frontController->throwExceptions(true);
                throw array_pop($exceptions);
            }
        }

        // check for an exception AND allow the error handler controller the
        // option to forward
        if (($response->isException()) && (!$this->_isInsideErrorHandlerLoop)) {
            $this->_isInsideErrorHandlerLoop = true;

            // Get exception information
            $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            $exceptions = $response->getException();
            $exception = $exceptions [0];
            $exceptionType = get_class($exception);
            $error->exception = $exception;

            switch ($exceptionType) {
                case 'Zend_Controller_Router_Exception' :
                    if (404 == $exception->getCode()) {
                        $error->type = self::EXCEPTION_NO_ROUTE;
                    } else {
                        $error->type = self::EXCEPTION_OTHER;
                    }
                    break;
                case 'Zend_Controller_Dispatcher_Exception' :
                    $error->type = self::EXCEPTION_NO_CONTROLLER;
                    break;
                case 'Zend_Controller_Action_Exception' :
                    if (404 == $exception->getCode()) {
                        $error->type = self::EXCEPTION_NO_ACTION;
                    } else {
                        $error->type = self::EXCEPTION_OTHER;
                    }
                    break;
                case 'App_Exception_PageNotFound' :
                    $error->type = self::EXCEPTION_ITEM_NOT_FOUND;
                    $this->setErrorHandlerAction('notfound');
                    break;
                case 'App_Exception_NoAccess' :
                    $error->type = self::EXCEPTION_NO_ACCESS;
                    $this->setErrorHandlerAction('noaccess');
                    break;
                case 'App_Exception_Maintenance' :
                    $error->type = self::EXCEPTION_MAINTENANCE;
                    $this->setErrorHandlerAction('maintenance');
                    break;
                // @todo add more cases for custom exceptions
                // case 'Core_Exception_ExceptionName' :
                // $error->type = self::EXCEPTION_NAME;
                // $this->setErrorHandlerAction('ExceptionName');
                // break;
                default :
                    $error->type = self::EXCEPTION_OTHER;
                    break;
            }

            // Keep a copy of the original request
            $error->request = clone $request;

            // get a count of the number of exceptions encountered
            $this->_exceptionCountAtFirstEncounter = count($exceptions);

            // Forward to the error handler
            $request->setParam('error_handler', $error)->setModuleName('default')->setControllerName($this->getErrorHandlerController())->setActionName($this->getErrorHandlerAction())->setDispatched(false);
        }
    }

}