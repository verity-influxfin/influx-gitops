<?php

namespace CreditSheet;

use CI_Controller;

class SpecialInfo
{
    private $meta_keys = [
        'job_company_taiwan_1000_point',
        'job_company_world_500_point',
        'job_company_medical_institute_point',
        'job_company_public_agency_point',
    ];
    /**
     * @var array
     */
    private $info = [];

    /**
     * @var int
     */
    private $target_id;
    /**
     * @var CI_Controller|object
     */
    private $CI;

    public function __construct(int $target_id)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/target_meta_model');
        $this->target_id = $target_id;
        $target_meta = $this->getTargetMeta($this->target_id, $this->meta_keys);
        $this->setInfo($target_meta);
    }

    /**
     * @param int $target_id
     * @param array $meta_keys
     * @return array|false
     */
    public function getTargetMeta(int $target_id, array $meta_keys)
    {
        $target_meta = $this->CI->target_meta_model->as_array()->get_many_by([
            'target_id' => $target_id,
            'meta_key' => $meta_keys
        ]);
        return array_column($target_meta, 'meta_value', 'meta_key');
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @param $target_meta
     * @return void
     */
    public function setInfo($target_meta): void
    {
        foreach ($this->meta_keys as $meta_key) {
            $this->info[$meta_key] = $target_meta[$meta_key] ?? 0;
        }
    }
}
