<?php

try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . $className . '.php';
    });

    //connect routes
    $routes = require_once __DIR__ . '/../src/routes.php';
    $route = $_GET['route'] ?? '';


    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    unset($matches[0]);

    if (!$isRouteFound) {
        throw new \xDeanBoy\Exceptions\NotFoundException('Сторінку не знайдено');
    }

    $controllerName = $controllerAndAction[0];
    $controllerAction = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$controllerAction(...$matches);

} catch (\xDeanBoy\Exceptions\DbException $e) {
    $view = new \xDeanBoy\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php',
        ['error' => $e->getMessage(),
            'title' => 'Error',
            'thisUser' => \xDeanBoy\Services\UserAuthServices::getUserByToken()],
        500);
    return;
} catch (\xDeanBoy\Exceptions\NotFoundException $e) {
    $view = new \xDeanBoy\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php',
        ['error' => $e->getMessage(),
            'title' => 'Error',
            'thisUser' => \xDeanBoy\Services\UserAuthServices::getUserByToken()],
        404);
    return;
} catch (\xDeanBoy\Exceptions\ForbiddenException $e) {
    $view = new \xDeanBoy\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php',
        ['error' => $e->getMessage(),
            'title' => 'Error',
            'thisUser' => \xDeanBoy\Services\UserAuthServices::getUserByToken()],
        403);
    return;
} catch (\xDeanBoy\Exceptions\UnauthorizedException $e) {
    $view = new \xDeanBoy\View\View( __DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['title' => 'Error'], 401);
    return;
}