<?php

namespace root;

use root\Base\RoutesConfigService;

class Router{
    private string $routerMethod;
    private string $routerPath;
    private array $routerQuery;

    private RoutesConfigService $routesConfigService;

    public function __construct(){
        $this->setRouterParams();
        $this->routesConfigService = new RoutesConfigService(dirname(__FILE__));
        $this->init();

    }

    /**
     * @param string $routerPath
     * @return Router
     */
    private function setRouterPath(string $routerPath): Router
    {
        $this->routerPath = substr(strtolower($routerPath), 1);
        return $this;
    }

    /**
     * @param string $routerQuery
     * @return Router
     */
    private function setRouterQuery(string $routerQuery): Router
    {
        $res = [];
        parse_str($routerQuery, $res);
        $this->routerQuery = $res;
        return $this;
    }

    /**
     * @return string
     */
    private function getRouterPath(): string
    {
        return $this->routerPath;
    }

    /**
     * @return array
     */
    public function getRouterQuery(): array
    {
        return $this->routerQuery;
    }

    /**
     * @param string $routerMethod
     * @return Router
     */
    private function setRouterMethod(string $routerMethod): Router
    {
        $this->routerMethod = strtolower($routerMethod);
        return $this;
    }

    /**
     * @return string
     */
    public function getRouterMethod(): string
    {
        return $this->routerMethod;
    }

    private function setRouterParams()
    {
        $parsedUri = parse_url($_SERVER['REQUEST_URI']);

        $this->setRouterPath($parsedUri['path'])
            ->setRouterMethod($_SERVER['REQUEST_METHOD'])
            ->setRouterQuery($parsedUri['query']??'');
    }

    private function init()
    {
        $configs = $this->routesConfigService->getConfigs();
        $config = $configs[$this->getRouterMethod()][$this->getRouterPath()];
        $controllerName = 'root\\'.$config['controller'].'\\'.$config['controller'].'Controller';
        $controller = $controllerName::getInstance();
        $method = $config['method'];
        $controller->$method($this->getRouterQuery()??null);
    }
}