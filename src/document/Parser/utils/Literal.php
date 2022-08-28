<?php


namespace iflow\template\document\Parser\utils;


class Literal {

    protected array $literalHtml = [];

    /**
     * 过滤需要原样输出的HTML代码
     * @param string $html
     * @return string
     */
    public function literal(string $html): string {
        // TODO: 原样输出代码

        preg_match_all('/(?:(literal)\b(?>(?:(?!).)*)|\/(literal))/is', $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        if (empty($matches)) return $html;

        $right = [];
        $tool = new Community();

        foreach ($matches as $match) {
            if ($match[1][0] !== '') {
                $right[strtolower($match[1][0])][] = $match[0];
                continue;
            }

            $name = strtolower($match[2][0]);

            // 闭合标签
            if (!empty($right[trim($name)])) {
                $begin = array_pop($right[trim($name)]);
                if (empty($right[trim($name)])) {
                    $blength = $begin[1] + 10;
                    $this->literalHtml[$tool -> create_uuid()] = substr($html, $blength, $match[0][1] - $blength - 1);
                }
            }
        }

        return $this->getHtml($html);
    }


    protected function getHtml(string $html): string {
        foreach ($this->literalHtml as $uuid => $_code) {
            $html = str_replace($_code, $uuid, $html);
        }
        return $html;
    }


    public function out_literal(string $html): string {
        foreach ($this->literalHtml as $key => $value) {
            $html = str_replace($key, $this->literalHtml[$key], $html);
        }
        return $html;
    }
}