<?php
/**
 * 匯出excel
 */
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Spreadsheet_lib
{
	/**
	 * 標題列
	 * $title_rows = [
	 *     'target_no' => [         //對應的資料列值
	 *         'name' => '案號',     //欄位標題
	 *         'width' => 10,       //欄位寬度
	 *         'alignment' => [
	 *             'h' => 'center', //水平對齊: center=置中, right=置右, left=置左
	 *             'v' => 'top'     //垂直對齊: center=置中, top=置頂, bottom=置底
	 *         ]
	 *     ],
	 * ]
	 *
	 * 資料列
	 * $data_rows = [
	 *     ['target_no' => 'STN2021091300001', 'user_id' => '47174'],
	 *     ['target_no' => 'STS2019061700001', 'user_id' => '487']
	 * ]
	 */
	function save($title_rows, $data_rows)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style = $spreadsheet->getDefaultStyle();
		$style->getFont()->setName('微軟正黑體');
		$style->getFont()->setSize(12);
		$style->getAlignment()->setWrapText(true);
		$style->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

		$this->draw_title($sheet, $title_rows);
		$this->draw_data($sheet, $title_rows, $data_rows);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="export2.xlsx"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	/**
	 * 畫標題
	 * @param $sheet
	 * @param $title_rows : 標題列
	 * @param int $row_index : 從第n列開始畫
	 * @return mixed
	 */
	function draw_title(&$sheet, $title_rows, $row_index = 1)
	{
		$column_counter = 0;
		foreach ($title_rows as $value) {
			$column_index = $this->ASCII_chr($column_counter);

			//寬度設定
			if (isset($value['width']) && !empty($value['width'])) {
				$sheet->getColumnDimension($column_index)->setWidth($value['width']);
			}

			//印標題
			$sheet->setCellValue($column_index . $row_index, $value['name']);

			//置中
			$alignment = $sheet->getStyle($column_index . $row_index)->getAlignment();
			$alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$alignment->setVertical(Alignment::VERTICAL_CENTER);
			$alignment->setWrapText(false);

			$column_counter++;
		}
	}

	/**
	 * 畫資料
	 * @param $sheet
	 * @param $title_rows : 標題列
	 * @param $data_rows : 資料列
	 * @param int $row_index : 從第n列開始畫
	 * @return mixed
	 */
	function draw_data(&$sheet, $title_rows, $data_rows, $row_index = 2)
	{
		foreach ($data_rows as $this_data_row) {
			$column_counter = 0;
			foreach ($title_rows as $key => $value) {
				$column_index = $this->ASCII_chr($column_counter);

				$data_value = '';
				if (isset($this_data_row[$key])) {
					$data_value = $this_data_row[$key];
				}

				$sheet->setCellValueExplicit($column_index . ($row_index), $data_value, DataType::TYPE_STRING);

				if (isset($value['alignment'])) {
					$alignment = $sheet->getStyle($column_index . $row_index)->getAlignment();

					//水平對齊
					if (isset($value['alignment']['h'])) {
						switch ($value['alignment']['h']) {
							case 'center':
								$alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
								break;
							case 'right':
								$alignment->setHorizontal(Alignment::HORIZONTAL_RIGHT);
								break;
							case 'left':
								$alignment->setHorizontal(Alignment::HORIZONTAL_LEFT);
								break;
						}
					}

					//垂直對齊
					if (isset($value['alignment']['v'])) {
						switch ($value['alignment']['v']) {
							case 'center':
								$alignment->setVertical(Alignment::VERTICAL_CENTER);
								break;
							case 'top':
								$alignment->setVertical(Alignment::VERTICAL_TOP);
								break;
							case 'bottom':
								$alignment->setVertical(Alignment::VERTICAL_BOTTOM);
								break;
						}
					}
				}

				$column_counter++;
			}

			$row_index++;
		}
	}

	/**
	 * 數字轉換成英文
	 * @param int $index
	 * @return string
	 */
	function ASCII_chr($index = 0)
	{
		if ($index < 26) {
			$result = chr(65 + $index);
		} elseif ($index < 702) {
			$result = chr(64 + ($index / 26)) . chr(65 + $index % 26);
		} else {
			$result = chr(64 + (($index - 26) / 676)) . chr(65 + ((($index - 26) % 676) / 26)) . chr(65 + $index % 26);
		}

		return $result;
	}
}
