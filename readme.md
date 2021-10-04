# iflow_template
基于DOM渲染的 PHP模板引擎 

# 安装

```shell
composer require iflow/template
```

# 使用方法

> 视图文件代码

```html
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>测试渲染</title>
        <!--   数值绑定     -->
        <link rel='stylesheet' :href="$main_css" />
    </head>
    <body>
    <!-- echo输出 与 echo 语法一致 -->
    <echo>$test</echo>
    <!--  数值绑定  -->
    <div :style="div_style"></div>
    
    <!-- 调用方法 action: 方法名称, props 方法参数,传参方法 props="$test,$test1,...." -->
    <function action="var_dump" props="$test" />
    <!-- 循环指令 -->
    <div i-for="$i = 0; $i < 10; $i++">
        <echo>$i</echo>
    </div>
    <div i-for="[1, 2, 3] as $number">
        <echo i-if="$number > 1">$number</echo>
    </div>
    <!-- 条件指令 -->
    <div i-if="is_string($test)">
        <echo>$test</echo>
    </div>
    <div i-elseif="$test === ''"><echo>$test</echo></div>
    <div i-else>才不是嘞</div>
    <phpscript>
        // PHP 代码块
        $a = "123";
        function test() {
            return "123123";
        }
        echo $a.test();
    </phpscript>
</body>
</html>

```

> PHP调用代码

```php
<?php
    use iflow\template\template;
    $config = [
        // 是否开启缓存
        'cache_enable' => false,
        // 缓存地址
        'store_path' => './runtime/template',
        'view_root_path' => './view' . DIRECTORY_SEPARATOR,
        'view_suffix' => 'html',
        'tags' => []
    ];
    
    $template = new template($config);
    
    $html = $template -> display("
        <html>
            <body>
                <echo>\$test</echo>
            </body>
        </html>
    ", [
        'test' => 123,
        'main_css' => 'statics/css/main.css',
        'type_style' => 'margin-top: 10px'
    ]);
    $html = $template -> fetch('index');
```

查看更多: https://www.yuque.com/youzhiyuandemao/ftorkm/zx0cp0