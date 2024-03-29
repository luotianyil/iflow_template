<?php


namespace iflow\template\document\Parser\instruction;


class forInstruction extends instructionAbstract
{

    public function getInstructionCode(): string
    {
        // TODO: Implement getInstructionCode() method.
        $instructionAttr = $this->DOMNodeParser -> getAttributes('i-for');

        // 解析FOR / FOREACH 指令
        $instructionAttrArray = explode(" ", $instructionAttr);
        for ($i = 1; $i < count($instructionAttrArray); $i++) {
            if ($instructionAttrArray[$i] === 'as') {
                return "<?php foreach ({$instructionAttr}): ?>%s<?php endforeach; ?>";
            }
        }
        return "<?php for ({$instructionAttr}): ?>%s<?php endfor; ?>";
    }
}