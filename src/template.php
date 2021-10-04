<?php


namespace iflow\template;

use iflow\template\config\Config;
use iflow\template\document\RenderView;
use iflow\template\exception\templateViewNotFound;
use iflow\template\interfaces\templateInterfaces;

class template implements templateInterfaces
{

    protected array $data = [];

    public function __construct(protected array|Config $config) {
        if (is_array($this->config)) {
            $this->config = new Config($this->config);
        }
    }

    /**
     * 设置配置文件
     * @param array|Config $config
     * @return $this
     */
    public function config(array|Config $config): static {
        if ($config instanceof Config) {
            $config = $this->config -> toArray();
        }
        $this->config -> reloadConfig($config);
        return $this;
    }

    /**
     * 渲染视图文件
     * @param string $template
     * @param array $vars
     * @param array|Config $config
     * @return string
     * @throws templateViewNotFound
     */
    public function fetch(string $template = '', array $vars = [], array|Config $config = []): string {
        if ($vars) {
            $this->data = array_merge($this->data, $vars);
        }

        if ($config) {
            $this->config($config);
        }

        $file = $this->config -> getViewRootPath() . $template . '.' . $this->config -> getViewSuffix();
        $this->exists($file);

        $content = file_get_contents($file);
        $viewRender = new RenderView($content);
        $viewRenderCode = $viewRender -> htmlToPHPCode($this->config);

        return $this->render(
            $this->saveCacheFile($template, $viewRenderCode)
        );
    }

    /**
     * 编译视图模板代码
     * @param string $content
     * @param array $vars
     * @param array|Config $config
     * @return string
     * @throws \Exception
     */
    public function display(string $content = '', array $vars = [], array|Config $config = []): string {
        if ($vars) {
            $this->data = array_merge($this->data, $vars);
        }

        if ($config) {
            $this->config($config);
        }

        $viewRender = new RenderView($content);
        $viewRenderCode = $viewRender -> htmlToPHPCode($this->config);

        return $this->render(
            $this->saveCacheFile($content, $viewRenderCode)
        );
    }

    /**
     * 设置模板变量
     * @param string $name
     * @param mixed $data
     * @return $this
     */
    public function assign(string $name, mixed $data): static
    {
        $this->data[$name] = $data;
        return $this;
    }

    /**
     * 获取文件缓存地址
     * @param string $template
     * @return string
     */
    protected function getCacheFile(string $template): string
    {
        return sprintf("%s/%s_%s.%s", ...[
            $this->config -> getStorePath(),
            $this->config -> getCachePrefix(),
            md5($template),
            $this->config -> getViewSuffix()
        ]);
    }

    /**
     * 储存编译后的视图文件
     * @param string $template
     * @param $content
     * @return string
     */
    protected function saveCacheFile(string $template, string $content): string
    {
        $cacheFile = $this->getCacheFile($template);
        $path = dirname($cacheFile);
        !is_dir($path) && mkdir($path, 0755, true);
        file_put_contents($cacheFile, $content);
        return $cacheFile;
    }

    /**
     * 检测缓存是否存在
     * @param string $template
     * @return bool
     */
    protected function checkCache(string $template): bool
    {
        if (!$this->config -> getCacheEnable()) {
            return false;
        }

        $cache = $this->getCacheFile($template);
        if (!file_exists($cache)) {
            return false;
        }

        $cacheTime = $this->config -> getCacheTime();
        if (0 !== $cacheTime && fileatime($template) + $cacheTime < time()) {
            return false;
        }

        return true;
    }

    /**
     * 执行视图模板
     * @param string $viewPath
     * @return string
     */
    public function render(string $viewPath): string
    {
        ob_start();
        ob_implicit_flush(0);
        extract($this->data, EXTR_OVERWRITE);
        include_once $viewPath;
        $info = ob_get_contents();
        ob_end_clean();
        return $info;
    }

    /**
     * 验证视图文件是否存在
     * @param string $file
     * @return bool
     * @throws templateViewNotFound
     */
    public function exists(string $file): bool
    {
        // TODO: Implement exists() method.
        if (!file_exists($file)) {
            throw new templateViewNotFound();
        }
        return true;
    }

    /**
     * @param array $data
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }
}