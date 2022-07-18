<?php

class Permission_lib
{
    public function mapping_permission_data(array $permission_list): array
    {
        if (empty($permission_list)) return [];
        array_walk($permission_list, function (&$value) use (&$result) {
            $result[$value['model_key']][$value['submodel_key']] = $value['action_type'];
        }, $result);

        return $result;
    }
}