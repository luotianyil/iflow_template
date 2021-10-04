<?php


namespace iflow\template\document\interfaces;

use iflow\template\config\Config;
use iflow\template\document\Parser\DOMNodeParser;
use iflow\template\document\Parser\ParserHtml;

interface TagInterfaces
{
    public function parser(DOMNodeParser $node, Config $config, ParserHtml $parserHtml): static;

    public function parserAttributes(): static;

    public function toHtml(): string;

    public function traverseNodesToHtml(): string;
}