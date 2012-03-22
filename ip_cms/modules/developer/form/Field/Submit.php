<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2012 ImpressPages LTD.
 * @license GNU/GPL, see ip_license.html
 */

namespace Modules\developer\form\Field;


class Submit extends Field{
    
    public function render($doctype) {
        return '<input class="ipfControlSubmit" name="'.htmlspecialchars($this->getName()).'" '.$this->getAttributesStr().' type="submit" value="'.htmlspecialchars($this->getDefaultValue()).'" />';
    }
    
    public function getLayout() {
        return self::LAYOUT_DEFAULT;
    }
    
    
    public function getType() {
        return self::TYPE_SYSTEM;
    }
    
}