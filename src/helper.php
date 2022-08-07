<?php

// 应用
if (!function_exists('app')) {
    function app(string $name = '', array $args = [], bool $isNew = false, ?callable $call = null): object {
        return new $name(...$args);
    }
}