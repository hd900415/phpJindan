<?php


namespace zpay\Common;


use ReflectionClass;

class AbstractModel
{
    public function serialize()
    {
        $ret = $this->objSerialize($this);
        return $ret;
    }
    private function objSerialize($obj) {
        $memberRet = [];
        $ref = new ReflectionClass(get_class($obj));
        $memberList = $ref->getProperties();
        foreach ($memberList as $x => $member)
        {
            $name = lcfirst($member->getName());
            $member->setAccessible(true);
            $value = $member->getValue($obj);
            if ($value === null) {
                continue;
            }
            if ($value instanceof AbstractModel) {
                $memberRet[$name] = $this->objSerialize($value);
            } else if (is_array($value)) {
                $memberRet[$name] = $this->arraySerialize($value);
            } else {
                $memberRet[$name] = $value;
            }
        }
        return $memberRet;
    }

    private function arraySerialize($memberList) {
        $memberRet = [];
        foreach ($memberList as $name => $value)
        {
            if ($value === null) {
                continue;
            }
            if ($value instanceof AbstractModel) {
                $memberRet[$name] = $this->objSerialize($value);
            } elseif (is_array($value)) {
                $memberRet[$name] = $this->arraySerialize($value);
            }else {
                $memberRet[$name] = $value;
            }
        }
        return $memberRet;
    }

    public function toJsonString()
    {
        $r = $this->serialize();
        // it is an object rather than an array
        if (empty($r)) {
            return "{}";
        }
        return json_encode($r, JSON_UNESCAPED_UNICODE);
    }

}