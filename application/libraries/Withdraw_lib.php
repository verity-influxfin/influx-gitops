<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Withdraw_lib
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('transaction/withdraw_model');
        $this->CI->load->model('transaction/frozen_amount_model');
    }

    public function withdraw_deny(array $withdraw_ids)
    {
        if (empty($withdraw_ids))
        {
            return FALSE;
        }

        $this->CI->withdraw_model->trans_begin();
        $this->CI->frozen_amount_model->trans_begin();

        $result = $this->CI->withdraw_model->get_affected_after_update([
            'status' => WITHDRAW_STATUS_CANCELED
        ], [
            'id' => $withdraw_ids,
            'status' => WITHDRAW_STATUS_WAITING,
            'frozen_id >' => 0
        ]);

        if ($result === 0)
        {
            $this->CI->withdraw_model->trans_rollback();
            $this->CI->frozen_amount_model->trans_rollback();
            return FALSE;
        }

        $frozen_ids = $this->CI->withdraw_model->get_frozen_id_list($withdraw_ids);
        $frozen_ids = array_column($frozen_ids, 'frozen_id');
        if (empty($frozen_ids))
        {
            $this->CI->withdraw_model->trans_rollback();
            $this->CI->frozen_amount_model->trans_rollback();
            return FALSE;
        }

        $result = $this->CI->frozen_amount_model->get_affected_after_update([
            'status' => 0
        ], [
            'id' => $frozen_ids
        ]);

        if ($result === 0)
        {
            $this->CI->withdraw_model->trans_rollback();
            $this->CI->frozen_amount_model->trans_rollback();
            return FALSE;
        }

        $this->CI->withdraw_model->trans_commit();
        $this->CI->frozen_amount_model->trans_commit();
        return TRUE;
    }
}