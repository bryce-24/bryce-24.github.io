<?php
require_once 'config.php';

// Simple routing system
class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $controllerFile = 'controllers/' . $route['controller'] . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controllerClass = $route['controller'];
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $route['action'])) {
                            $controller->{$route['action']}();
                            return;
                        }
                    }
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    private function matchPath($routePath, $requestPath) {
        // Simple exact match for now
        if ($routePath === $requestPath) {
            return true;
        }
        
        // Handle parameter matching (e.g., /unsubscribe/{token})
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        
        if (preg_match($routePattern, $requestPath, $matches)) {
            array_shift($matches);
            $_GET['params'] = $matches;
            return true;
        }
        
        return false;
    }
}

// Define routes
$router = new Router();

// Subscription routes
$router->addRoute('GET', '/business.php', 'SubscriptionController', 'showSubscribe');
$router->addRoute('POST', '/business.php', 'SubscriptionController', 'handleSubscribe');
$router->addRoute('GET', '/business/subscribe', 'SubscriptionController', 'showSubscribe');
$router->addRoute('POST', '/business/subscribe', 'SubscriptionController', 'handleSubscribe');

// Management routes
$router->addRoute('GET', '/business/manage', 'SubscriptionController', 'showManage');
$router->addRoute('POST', '/business/manage', 'SubscriptionController', 'handleManage');

// Unsubscribe routes
$router->addRoute('GET', '/business/unsubscribe', 'SubscriptionController', 'showUnsubscribe');
$router->addRoute('GET', '/business/unsubscribe/{token}', 'SubscriptionController', 'handleUnsubscribe');
$router->addRoute('POST', '/business/unsubscribe', 'SubscriptionController', 'handleUnsubscribeByEmail');

// Confirmation route
$router->addRoute('GET', '/business/confirm', 'SubscriptionController', 'showConfirm');

// Admin routes (require authentication)
$router->addRoute('GET', '/business/admin', 'AdminController', 'showAdmin');
$router->addRoute('POST', '/business/admin/update', 'AdminController', 'updateSubscription');
$router->addRoute('POST', '/business/admin/delete', 'AdminController', 'deleteSubscription');
$router->addRoute('POST', '/business/admin/send', 'AdminController', 'sendTestEmail');

// Dispatch the request
$router->dispatch();
?>

