<?php


namespace iflow\template\document\Parser;


use iflow\template\config\Config;
use iflow\template\document\RenderView;

class ParserHtml
{

    protected PHPTag $PHPTag;
    protected ParserInstruction $ParserInstruction;

    public function __construct(
        protected Config $config,
        protected RenderView $document
    ) {
        $this->PHPTag = new PHPTag($this->config);
        $this->ParserInstruction = new ParserInstruction($this->config);
    }

    /**
     * 遍历节点获取HTML代码
     * @param \DOMNode|DOMNodeParser|null $htmlNode
     * @return string
     * @throws \Exception
     */
    public function traverseNodes(\DOMNode|DOMNodeParser $htmlNode = null): string
    {
        $nodes = $htmlNode ?: $this->document -> getHtmlNode();
        if (!$nodes) {
            return "";
        }

        $html = "";
        foreach ($nodes -> childNodes as $item) {
            if ($item instanceof \DOMText) {
                $html .= $item -> C14N();
                continue;
            }
            $dom = new DOMNodeParser($item);
            $parserPhp = $this->parserPHPTag($dom);
            if ($dom -> childNodes -> count() > 0) {
                if ($parserPhp) {
                    $html .= $parserPhp;
                } else {
                    $html .= $this->parserPHPInstruction(
                        $dom,
                        $dom -> innerHtml($this->config -> getHiddenAttributes(), $this->traverseNodes($dom))
                    );
                }
            } else {
                $html .= $parserPhp ?: $this->parserPHPInstruction(
                    $dom, $dom -> innerHtml($this->config -> getHiddenAttributes())
                );
            }
        }

        return html_entity_decode($html);
    }

    /**
     * 解析 PHP 自定义DOM
     * @param DOMNodeParser $DOMNodeParser
     * @return string|null
     * @throws \Exception
     */
    public function parserPHPTag(DOMNodeParser $DOMNodeParser): string|null
    {
        return $this->PHPTag -> parserTag($DOMNodeParser, $this);
    }

    /**
     * 解析DOM 指令
     * @param DOMNodeParser $DOMNodeParser
     * @param string $html
     * @return string
     */
    public function parserPHPInstruction(DOMNodeParser $DOMNodeParser, string $html): string
    {
        return $this->ParserInstruction -> parserInstruction($DOMNodeParser, $html);
    }

    /**
     * 获取配置
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
}