<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

// application/core/MY_ReadOnly_Controller.php

/**
  * Base controller for endpoints that never write session data
  * (api/* and Ajax.php). CI3's database session driver holds a MySQL
  * GET_LOCK() on the session row from the moment the session is first
  * touched (in MY_Controller's constructor) until the script ends, so
  * concurrent AJAX calls sharing one session cookie serialize behind
  * whichever request holds the lock longest. Closing the session
  * write lock immediately after MY_Controller's constructor runs lets
  * these read-only requests run in parallel instead.
  *
  * Do not extend this from a controller that calls loginUser(),
  * logoutUser(), updateUser(), updateIntervals(), or registerUser()
  * (application/helpers/user_helper.php), or that writes $_SESSION
  * directly - those need the lock held for the rest of the request,
  * or must call session_start() again right before writing (see
  * Ajax::selectYourself()).
  */
class MY_ReadOnly_Controller extends MY_Controller {
  public function __construct() {
    parent::__construct();
    session_write_close();
  }
}