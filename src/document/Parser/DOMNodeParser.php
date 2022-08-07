<?php


namespace iflow\template\document\Parser;

use DOMNode;
use DOMText;
use iflow\template\document\Parser\utils\AttributeBindValue;

/**
 * @mixin DOMNode
 * @mixin DOMText
 * Class DOMNodeParser
 * @package iflow\template\lib\document\Parser
 */
class DOMNodeParser
{

    protected AttributeBindValue $attributeBindValue;

    // 解析 DOMNode
    public function __construct(protected DOMNode|DOMText $DOMNode) {
        $this->attributeBindValue = new AttributeBindValue($this);
    }

    /**
     * 获取 attributes
     * @param array|string $keys
     * @return array|string|null
     */
    public function getAttributes(
        array|string $keys = ""
    ): array|string|null {

        if (!$this->DOMNode -> attributes) return null;

        $attr = [];
        // 当key为空时获取全部
        if (!$keys) {
            foreach ($this->DOMNode -> attributes as $item) {
                $attr[$item -> nodeName] = $item -> nodeValue;
            }
            return $attr;
        }

        if (is_string($keys)) {
            return $this->DOMNode -> attributes ?-> getNamedItem(strtolower($keys)) ?-> textContent;
        }

        foreach ($keys as $key) {
            $attr[$key] = $this->DOMNode -> attributes -> getNamedItem($key) ?-> textContent;
        }
        return $attr;
    }


    /**
     * 获取 TAG属性
     * @param array $hidden 需要排除的 Tag属性
     * @param bool $bindValue 是否绑定变量
     * @param DOMNodeParser|array $DOMNodeParser
     * @return string
     */
    public function getAttributesToString(array $hidden = [], bool $bindValue = false, DOMNodeParser|array $DOMNodeParser = []): string
    {
        if ($bindValue) {
            if (!$DOMNodeParser) $this->attributeBindValue -> setDOMNodeParser($DOMNodeParser);
            return $this->attributeBindValue -> setAttributesValue($hidden) -> attributesToString();
        }

        $attr = "";
        foreach ($this->DOMNode -> attributes as $item) {
            if (!in_array($item -> nodeName, $hidden)) {
                $nodeValue = $item -> nodeName === 'attr-anchor' ? "#{$item -> nodeValue}" : $item -> nodeValue;
                $attr .= "{$item -> nodeName}=\"{$nodeValue}\" ";
            }
        }
        return trim($attr);
    }

    /**
     * 获取当前节点内容
     * @param array $hiddenAttr 需要排除的 Tag属性
     * @param string $content 当前节点内容
     * @return string
     */
    public function innerHtml(array $hiddenAttr = [], string $content = ""): string
    {
        $node = $this->DOMNode -> nodeName;
        $node = match ($node) {
            '#comment' => '<!-- %s %s -->',
            default => "<$node %s>%s</$node>",
        };

        return sprintf($node, ...[
            $this -> getAttributesToString($hiddenAttr, true, $this),
            $content ?: $this->DOMNode -> nodeValue
        ]);
    }

    /**
     * @param DOMNode $attribute
     * @return DOMNodeParser
     */
    public function setAttributes(DOMNode $attribute): DOMNodeParser
    {
        $this->DOMNode -> attributes -> setNamedItem($attribute);
        return $this;
    }

    /**
     * @return DOMNode
     */
    public function getDOMNode(): DOMNode
    {
        return $this->DOMNode;
    }

    public function getChildrenList(): \DOMNodeList
    {
        return $this->DOMNode -> childNodes;
    }

    /**
     * 获取下层DOM
     * @param ?DOMNode $node
     * @param bool $isText
     * @return DOMNodeParser|null
     */
    public function getNextNode(?DOMNode $node, bool $isText = false): ?DOMNodeParser
    {
        if ($node instanceof \DOMText && !$isText) {
            return $this->getNextNode($node -> nextSibling);
        }

        if (!$node) {
            return null;
        }
        return new DOMNodeParser($node);
    }

    /**
     * 将下级子节点以字符串方式返回
     * @return string
     */
    public function toString(): string {
        $innerHTML ="";
        $children  = $this->childNodes;
        foreach ($children as $child)  {
            $innerHTML .= $this->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    public function __get(string $name)
    {
        // TODO: Implement __get() method.
        return $this->DOMNode -> {$name};
    }

    /**
     * 方法异常回调
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        // TODO: Implement __call() method.
        return call_user_func([$this->DOMNode, $name], ...$arguments);
    }

}