<?php


namespace iflow\template\document\Parser\utils;


class Literal
{

    protected array $literalHtml = [];


    /**
     * 过滤需要原样输出的HTML代码
     * @param string $html
     * @return string
     */
    public function literal(string $html): string
    {
        // TODO: 原样输出代码
        preg_match_all(
            "/(\<literal(.*|)\>)([\s\S]*?)(\<\/literal\>)/i",
            $html,
            $html_literal
        );

        if (empty($html_literal[0])) return $html;

        foreach ($html_literal[0] as $iKey => $tag) {
            $uuid = uniqid('iflowTemplate_literal_'). (new Community()) -> create_uuid();
            $html = str_replace($tag, $uuid, $html);
            $this->literalHtml[$uuid] = $html_literal[3][$iKey];
        }
        return $html;
    }


    public function out_literal(string $html): string
    {
        foreach ($this->literalHtml as $key => $value) {
            $html = str_replace($key, $this->literalHtml[$key], $html);
        }
        return $html;
    }
}