<?php


namespace EdmEvent;


class EdmEventFactory {
    public static function getInstance($event): ?EdmEventBase
    {
        $returnObject = NULL;
        if(isset($event['id'])) {
            try{
                switch ($event['id']) {
                    case 1:
                        // EdmEvent1
                        break;
                    case 2:
                        // EdmEvent2
                        break;
                    default:
                        error_log("Nonsupport Event ID : ".$event['id']);
                        break;
                }
            }catch (\Exception $e) {
                error_log("Exception: ". $e->getMessage());
            }
        }

        return $returnObject;
    }
}
