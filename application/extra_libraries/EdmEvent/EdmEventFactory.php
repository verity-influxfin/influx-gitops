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
                        $returnObject = new EdmEvent1($event);
                        break;
                    case 2:
                        $returnObject = new EdmEvent2($event);
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
