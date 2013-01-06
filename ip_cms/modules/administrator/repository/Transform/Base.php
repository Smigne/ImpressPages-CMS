<?php
    /**
     * @package   ImpressPages
     * @copyright Copyright (C) 2012 JSC Apro Media.
     * @license   GNU/GPL, see ip_license.html
     */

namespace Modules\administrator\repository\Transform;

abstract class Base
{
    public abstract function transform($sourceFile, $destinationFile);

    public function getParamStr()
    {
        return serialize(get_object_vars($this));

    }

    final public function getFingerprint()
    {
        return md5(__CLASS__.':'.$this->getParamStr());
    }

}