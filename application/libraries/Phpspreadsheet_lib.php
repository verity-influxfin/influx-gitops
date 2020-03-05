<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Phpspreadsheet_lib
{

    function __construct()
    {
        $this->CI = &get_instance();
    }

	public function excel($filename,$data,$title='',$subject='',$descri='',$user_id='',$export=true,$sum=false,$merge_sum=false,$merge_title = false){
        if($data){
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()
                ->setCompany('普匯金融科技股份有限公司')
                ->setCreator(GMAIL_SMTP_NAME)
                ->setLastModifiedBy(GMAIL_SMTP_NAME)
                ->setTitle($title)
                ->setSubject($subject)
                ->setKeywords('')
                ->setCategory('')
                ->setDescription($descri);

            foreach ($data as $sheet => $contents){
                $sheet>0?$spreadsheet->createSheet():'';
                $row = 1;
                if($merge_title){
                        foreach ($merge_title as $mergeTitleIndex => $mergeTitle) {
                            $cut = explode(':',$mergeTitleIndex);
                            $spreadsheet->setActiveSheetIndex($sheet)->mergeCells($this->num2alpha($cut[0]) .'1:' . $this->num2alpha($cut[1]) . '1');
                        }
                        $row++;
                }
                foreach ($contents['title'] as $titleIndex => $title) {
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($titleIndex).($row), $title);
                }
                $row++;
                foreach ($contents['content'] as $rowInddex => $rowContent) {
                    foreach ($rowContent as $rowContentInddex => $rowValue) {
                        $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($rowContentInddex) . ($row), $rowValue);
                    }
                    $row++;
                }
                if($sum){
                    foreach ($contents['title'] as $titleIndex => $titleContent) {
                        $spreadsheet->getActiveSheet($sheet)->getStyle($this->num2alpha($titleIndex).'1')->getFont()->setSize(13);
                        if($titleIndex==0){
                            $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha(0).($row), '合計');
                        }
                        else{
                            if(in_array($titleIndex,$sum)){
                                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($titleIndex).($row), '=SUM('.$this->num2alpha($titleIndex).'1:'.$this->num2alpha($titleIndex).($row-1).')');
                            }
                        }
                    }
                    $row++;
                }
                if($merge_sum){
                    foreach ($contents['title'] as $titleIndex => $titleContent) {
                        if($titleIndex==0){
                            $nrow = $row-($sum?1:0);
                            $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha(0).($row), '');
                            $merge_sum?$spreadsheet->setActiveSheetIndex($sheet)->mergeCells($this->num2alpha(0).($nrow).':'.$this->num2alpha(0).($row)):'';
                        }else{
                            if($merge_sum[0]==$titleIndex){
                                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($titleIndex).($row), '=SUM('.$this->num2alpha($merge_sum[0]).($row-1).':'.$this->num2alpha($merge_sum[1]).($row-1).')');
                                $spreadsheet->setActiveSheetIndex($sheet)->mergeCells($this->num2alpha($merge_sum[0]).($row).':'.$this->num2alpha($merge_sum[1]).($row));
                            }
                        }
                    }
                }
                $spreadsheet->setActiveSheetIndex($sheet)->setTitle($contents['sheet']);
                $spreadsheet->getActiveSheet($sheet)->getDefaultColumnDimension()->setWidth(20);

            }
            $spreadsheet->setActiveSheetIndex(0);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            if($export){
                ob_end_clean();
                ob_start();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: cache, must-revalidate');
                header('Pragma: public');
                $writer->save('php://output');
                exit;
            }
            else{
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($filename);
                $filepath = file_get_contents($filename);
                $this->CI->load->library('S3_upload');
                $file_url = $this->CI->s3_upload->excel($filepath,$filename,$user_id,'estatement/temp');
                if($file_url){
                    unlink($filename);
                    return $file_url;
                }
            }
            return false;
        }
	}

	private function num2alpha($n){
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }
}