# iflow_template
基于DOM渲染的 PHP模板引擎 


# 使用方法

```html

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>测试渲染</title>
    </head>
    <body>
    <!-- echo输出 与 echo 语法一致 -->
    <echo>$test</echo>
    
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
    <!-- i-else 必须要带内容(占位符) 不能为空 -->
    <div i-else="true">才不是嘞</div>
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

查看更多: https://www.yuque.com/youzhiyuandemao/ftorkm/zx0cp0