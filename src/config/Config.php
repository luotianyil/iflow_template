<?php


namespace iflow\template\config;


use iflow\template\document\Parser\instruction\forInstruction;
use iflow\template\document\Parser\instruction\ifInstruction;
use iflow\template\document\Tag\Db;
use iflow\template\document\Tag\echoTag;
use iflow\template\document\Tag\functionTag;
use iflow\template\document\Tag\includeTag;
use iflow\template\document\Tag\Literal;
use iflow\template\document\Tag\PHPScript;

class Config
{
    protected array $defaultConfig = [
        // 自定义TAG 名称
        'tags' => [
            'db' => [
                'class' => Db::class
            ],
            'phpscript' => [
                'class' => PHPScript::class
            ],
            'echo' => [
                'class' => echoTag::class
            ],
            'function' => [
                'class' => functionTag::class
            ],
            'include' => [
                'class' => includeTag::class
            ]
        ],
        // 自定义指令
        'instruction' => [
            'i-if' => ifInstruction::class,
            'i-elseif' => ifInstruction::class,
            'i-else' => ifInstruction::class,
            'i-for' => forInstruction::class
        ],
        // 需要隐藏的全局tag属性
        'hidden-attributes' => [
            'i-if', 'i-elseif', 'i-else', 'i-for', 'tag'
        ],
        // 文件尾缀
        'view_suffix' => 'html',
        // 是否开启缓存
        'cache_enable' => true,
        // 缓存文件前缀
        'cache_prefix' => 'template',
        // 缓存地址
        'store_path' => '',
        // 视图目录
        'view_root_path' => '',
        // 缓存时间
        'cache_time' => 0
    ];

    public function __construct(
        public array $config = []
    ) {
        $this->config = array_replace_recursive($this->defaultConfig, $this->config) ?: [];
    }

    /**
     * 设置配置
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function setConfig(string $name, mixed $value): bool
    {
        $this->config[$name] = $value;
        return true;
    }

    /**
     * 是否开启缓存
     * @return bool
     */
    public function getCacheEnable(): bool
    {
        return $this->config['cache_enable'] ?? false;
    }

    /**
     * 获取缓存地址
     * @return string
     */
    public function getStorePath(): string
    {
        return $this->config["store_path"] ?? "";
    }

    /**
     * 获取缓存文件前缀
     * @return string
     */
    public function getCachePrefix(): string
    {
        return $this->config['cache_prefix'];
    }

    /**
     * 获取缓存文件时间
     * @return mixed
     */
    public function getCacheTime(): int
    {
        return $this->config['cache_time'];
    }

    /**
     * 获取视图根目录
     * @return string
     */
    public function getViewRootPath(): string
    {
        return $this->config['view_root_path'] ?? '';
    }

    /**
     * 获取视图文件后缀
     * @return string
     */
    public function getViewSuffix(): string
    {
        return $this->config['view_suffix'] ?? '';
    }

    /**
     * 获取自定义指令
     * @return mixed
     */
    public function getInstruction(): array
    {
        return $this->config['instruction'];
    }

    /**
     * 获取需要隐藏的全局tag属性
     * @return array
     */
    public function getHiddenAttributes(): array
    {
        return $this->config['hidden-attributes'] ?? [];
    }

    /**
     * 获取 自定义 TAG
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getTagByName(string $name, array $default = []): array
    {
        return $this->config['tags'][$name] ?? $default;
    }

    /**
     * 获取原样config配置
     * @return array
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * 重置配置
     * @param array $config
     */
    public function reloadConfig(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }
}