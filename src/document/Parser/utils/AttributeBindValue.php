<?php


namespace iflow\template\document\Parser\utils;


use iflow\template\document\Parser\DOMNodeParser;

class AttributeBindValue
{

    protected string $attrs = "";

    public function __construct(protected DOMNodeParser|array $DOMNodeParser) {
        $this->setDOMNodeParser($this->DOMNodeParser);
    }

    /**
     * 关联变量
     * @param array $hidden | 需要隐藏的TAG
     * @return static
     */
    public function setAttributesValue(array $hidden = []): static {
        foreach ($this->DOMNodeParser as $key => $value) {
            if (!in_array($key, $hidden)) {
                if (str_starts_with($key, ':')) {
                    $key = ltrim($key, ':');
                    $nodeValue = $key === 'attr-anchor' ? "#<?=$value?>" : "\"<?=$value?>\"";
                } else {
                    $nodeValue = $key === 'attr-anchor' ? "#$value" : "\"$value\"";
                }
                $key = $key === 'attr-anchor' ? '' : "$key=";
                $this->attrs .= " $key$nodeValue";
            }
        }
        return $this;
    }

    /**
     * 获取当前TAG属性
     * @return string
     */
    public function attributesToString(): string
    {
        return trim($this->attrs);
    }

    /**
     * @param DOMNodeParser|array $DOMNodeParser
     * @return static
     */
    public function setDOMNodeParser(DOMNodeParser|array $DOMNodeParser): static
    {
        if ($DOMNodeParser instanceof DOMNodeParser) {
            $DOMNodeParser = $DOMNodeParser -> getAttributes() ?: [];
        }
        $this->DOMNodeParser = $DOMNodeParser;
        return $this;
    }
}