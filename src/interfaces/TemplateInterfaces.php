<?php


namespace iflow\template\interfaces;


use iflow\template\config\Config;

interface TemplateInterfaces
{
    public function config(array|Config $config);

    public function exists(string $file);

    public function display(string $content = '', array $vars = [], array|Config $config = []);

    public function fetch(string $template = '', array $vars = [], array|Config $config = []);
}