<?php

namespace Approve_target;

use Approve_target\Credit\Product_home_loan_appliances;
use Approve_target\Credit\Product_home_loan_renovation;
use Approve_target\Credit\Product_home_loan_short;
use Approve_target\Credit\Product_student;
use Exception;

class Approve_factory
{
    private $CI;

    private $target;

    private $error_message;

    public function get_instance_by_model_data($target): ?Approve_base
    {
        $this->CI = &get_instance();

        $this->target = $target;

        try {
            $this->_target_validation();

            $instance = $this->_get_instance_by_target() ?? null;

            if (empty($instance)) {
                $this->error_message = "欲建立未支援的產品項 ({$this->target['product_id']}) ";
                log_message('error', $this->error_message);
                return null;
            }
            return $instance;
        } catch (\Exception $e) {
            if ($this->error_message == '') {
                log_message('error', 'Approve target 出現錯誤的存取 (' . $e->getMessage() . ')');
            }
            return null;
        }
    }

    /**
     * @throws Exception
     */
    private function _target_validation(): void
    {
        if (is_object($this->target)) {
            $this->target = json_decode(json_encode($this->target), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->_log_error_msg();
            }
        } elseif (!is_array($this->target)) {
            $this->_log_error_msg();
        }

        if (!isset($this->target['product_id']) || !isset($this->target['sub_product_id'])) {
            $this->_log_error_msg();
        }
    }


    private function _get_instance_by_target(): ?Approve_base
    {
        $instance = null;
        switch ($this->target['product_id']) {
            default:
                break;
            case PRODUCT_ID_STUDENT:
                // 0: 學生貸 1: 學生工程師貸
                if ($this->target['sub_product_id'] === 0) {
                    $instance = new Product_student($this->target);
                }
                break;
                // case PRODUCT_ID_SALARY_MAN:
                //     switch ($this->target['sub_product_id'])
                //     {
                //         default:
                //             break;
                //         case 0: // 上班族貸
                //             break;
                //         case 1: // 上班族工程師貸
                //             break;
                //     }
                //     break;
            case PRODUCT_ID_HOME_LOAN:
                switch ($this->target['sub_product_id']) {
                    case SUB_PRODUCT_ID_HOME_LOAN_SHORT:
                        $instance = new Product_home_loan_short($this->target);
                        break;
                    case SUB_PRODUCT_ID_HOME_LOAN_RENOVATION:
                        $instance = new Product_home_loan_renovation($this->target);
                        break;
                    case SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES:
                        $instance = new Product_home_loan_appliances($this->target);
                        break;
                    default:
                        break;
                }
                break;
        }
        return $instance;
    }

    /**
     * @throws Exception
     */
    private function _log_error_msg(): void
    {
        $this->error_message = 'Approve target 出現錯誤的存取';
        log_msg('error', $this->error_message);
        throw new Exception($this->error_message);
    }
}
