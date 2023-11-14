<?php
namespace Certification\Report;

interface CertificationDefinition
{
    public const TYPE_PERSONAL = 1;
    public const TYPE_COMPANY = 2;
    public const TYPE_LIST = [
        self::TYPE_PERSONAL => "personal",
        self::TYPE_COMPANY => "company",
    ];
    public const CERTIFICATION_STATUS_PENDING_TO_VALIDATE = 0;
    public const CERTIFICATION_STATUS_SUCCEED = 1;
    public const CERTIFICATION_STATUS_FAILED = 2;
    public const CERTIFICATION_STATUS_PENDING_TO_REVIEW = 3;
    public const CERTIFICATION_STATUS_NOT_COMPLETED = 4;
    public const CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION = 5;
    public const CERTIFICATION_STATUS_AUTHENTICATED = 6;

    public const TARGET_ASSOCIATES_RELATIONSIHP_SPOUSE = 0;
    public const TARGET_ASSOCIATES_RELATIONSIHP_BLOOD_RELATIVE = 1;
    public const TARGET_ASSOCIATES_RELATIONSIHP_IN_LAW = 2;
    public const TARGET_ASSOCIATES_RELATIONSIHP_SHAREHOLDER = 3;
    public const TARGET_ASSOCIATES_RELATIONSIHP_FRIEND = 4;
    public const TARGET_ASSOCIATES_RELATIONSIHP_SELF = 5;
    public const TARGET_ASSOCIATES_RELATIONSIHP_OTHER = 6;
    public const TARGET_ASSOCIATES_RELATIONSIHP_STAFF = 7;
}
