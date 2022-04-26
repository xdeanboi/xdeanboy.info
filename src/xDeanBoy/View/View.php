<?php

namespace xDeanBoy\View;

class View
{
    private $templatePath;
    private $exctractVars = [];

    /**
     * @param string $templatePath
     */
    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $varName
     * @param $value
     * @return void
     */
    public function setVars(string $varName, $value)
    {
        $this->exctractVars[$varName] = $value;
    }

    /**
     * @param string $templateName
     * @param array $templateVars
     * @param int $code
     * @return void
     */
    public function renderHtml(string $templateName, array $templateVars = [], int $code = 200)
    {
        http_response_code($code);

        extract($this->exctractVars);
        extract($templateVars);

        ob_start();
        include $this->templatePath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }
}