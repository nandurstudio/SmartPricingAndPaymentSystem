<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MenuModel;

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
        parent::initController($request, $response, $logger);        // Initialize menu data
        $this->menuModel = new MenuModel();
        $roleID = session()->get('roleID');
        $roleName = session()->get('roleName') ?? 'Unknown';
        
        if ($roleID) {
            try {
                // Get menus from model - already grouped by parent ID
                $menuGroups = $this->menuModel->getMenusByRole($roleID);
                
                // Check if we have at least some parent menus
                if (empty($menuGroups) || !isset($menuGroups[0]) || empty($menuGroups[0])) {
                    // Log this as a warning
                    log_message('warning', "Role {$roleName} (ID: {$roleID}) has no parent menus assigned.");
                    
                    // If in development, show this as a flash message
                    if (ENVIRONMENT === 'development') {
                        session()->setFlashdata('warning', "Role {$roleName} has no parent menus assigned. Check your database configuration.");
                    }
                }
                
                // Store both the raw menus and grouped menus
                $this->data['menus'] = $menuGroups;
                $this->data['menuGroups'] = $menuGroups;
                
                // Debug logging for troubleshooting
                log_message('debug', "Menu Data loaded for role {$roleName} (ID: {$roleID}). Found " . 
                    (isset($menuGroups[0]) ? count($menuGroups[0]) : 0) . " parent menus.");
            } catch (\Exception $e) {
                log_message('error', "Error loading menu for role {$roleName} (ID: {$roleID}): " . $e->getMessage());
                // Set empty arrays on error
                $this->data['menus'] = [];
                $this->data['menuGroups'] = [];
            }
        } else {
            // Set empty arrays if no role ID
            $this->data['menus'] = [];
            $this->data['menuGroups'] = [];
            log_message('debug', 'No roleID found in session, menu not loaded');
        }

        // Initialize Properties
        $this->session = \Config\Services::session();
    }

    /**
     * Load menu based on user role
     */    protected function loadUserMenu()
    {
        if (session()->get('isLoggedIn')) {
            $roleID = session()->get('roleID');
            
            // Debug logging
            // log_message('debug', 'Loading menu for roleID: ' . ($roleID ?? 'null'));
            // log_message('debug', 'Session data: ' . print_r(session()->get(), true));
            
            $menus = $this->menuModel->getMenusByRole($roleID);
            
            // Debug logging
            // log_message('debug', 'Loaded menus: ' . print_r($menus, true));
            
            // Store menu in view data
            $this->data['menus'] = $menus;
            try {
                // If no parent menus exist, issue a warning in development
                if (empty($menus[0]) && ENVIRONMENT === 'development') {
                    $roleName = session()->get('roleName') ?? 'Unknown';
                    // If in development, show this as a flash message
                    if (ENVIRONMENT === 'development') {
                        session()->setFlashdata('warning', "Role {$roleName} has no parent menus assigned. Check your database configuration.");
                    }
                }
                
                // Store both the raw menus and grouped menus
                $this->data['menus'] = $menus;
                $this->data['menuGroups'] = $menus;
                
                // Debug logging for troubleshooting
                // log_message('debug', "Menu Data loaded for role {$roleName} (ID: {$roleID}). Found " . 
                //     (isset($menuGroups[0]) ? count($menuGroups[0]) : 0) . " parent menus.");
            } catch (\Exception $e) {
                log_message('error', "Error loading menu for role {$roleName} (ID: {$roleID}): " . $e->getMessage());
                // Set empty arrays on error
                $this->data['menus'] = [];
                $this->data['menuGroups'] = [];
            }
        } else {
            log_message('debug', 'User not logged in, no menu loaded');
            $this->data['menus'] = [];
            $this->data['menuGroups'] = [];
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
