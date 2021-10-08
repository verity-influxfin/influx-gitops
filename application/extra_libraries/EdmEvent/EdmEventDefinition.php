<?php

namespace EdmEvent;

interface EdmEventDefinition
{
    public const TYPE_EMAIL = 0;
    public const TYPE_LIST = [
        self::TYPE_EMAIL => "電子郵件",
    ];
}