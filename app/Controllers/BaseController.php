<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */    protected $helpers = ['url', 'form', 'auth'];

    /**
     * Properties for storing shared data
     */
    protected $data = [];
    protected $menuModel;
    protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Initialize session
        $this->session = \Config\Services::session();
        
        // Load MenuModel
        $this->menuModel = new \App\Models\MenuModel();
        
        // Load menu for logged in user
        $this->loadUserMenu();
    }

    /**
     * Load menu based on user role
     */
    protected function loadUserMenu()
    {
        if (session()->get('isLoggedIn')) {
            $roleId = session()->get('roleId');
            $menus = $this->menuModel->getMenusByRole($roleId);
            
            // Store menu in view data
            $this->data['menus'] = $menus;
        }
    }

    /**
     * Load view with common data
     */
    protected function render(string $view, array $data = [])
    {
        // Merge any data provided with the menu data
        $viewData = array_merge($this->data ?? [], $data);
        
        return view($view, $viewData);
    }
}
