<?php

namespace LinkCMS\Modules\WPImporter\Model;

class ImportContent {
    public function __construct($htmlContent) {
        $this->settings = new \stdClass();
        $this->settings->id = '';
        $this->settings->class = '';
        $this->settings->textColor = '';
        $this->blockVisible = true;
        $this->backgroundColor = '';
        $this->content = $htmlContent;
        $this->type = 'paragraph';
    }
}