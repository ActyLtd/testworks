<?php

namespace root\Base;

class RoutesConfigService
{
    private array $configs = [
        'post' => [],
        'get' => []
    ];
    private string $basePath = '';

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->prepareConfigs($basePath);
    }

    private function prepareConfigs(string $path)
    {
        $dirList = [];
        $dirList = scandir($path);
        foreach ($dirList as $item) {
            if(is_dir($this->basePath.'/'.$item) && file_exists($this->basePath.'/'.$item.'/routes.php')) {
                $config = require ($this->basePath.'/'.$item.'/routes.php');
                $this->configs['post'] = array_merge($this->configs['post'], $config['post']);
                $this->configs['get'] = array_merge($this->configs['get'], $config['get']);
            }
        }
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }
}