<?php 
    namespace app\controllers;
    use app\core\Controller;
    class AuthController extends Controller {
        public function __construct() {
            // $this->middleware('auth');
        }
    
        public function index($req, $res) {
            $res->render('/users/index');
        }
    
        public function profile($req, $res) {
            $res->render('/users/profile');
        }
    
        public function register($req, $res) {
            $res->render('/auth/register');
        }
    }
?>