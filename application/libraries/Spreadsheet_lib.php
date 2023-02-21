<?php
/**
 * 匯出excel
 */
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;


class Spreadsheet_lib
{
    public $row_index;
    public function __construct()
    {
        $this->row_index = 1;
    }

    /**
	 * 標題列
	 * $title_rows = [
	 *     'target_no' => [         //對應的資料列值
	 *         'name' => '案號',     //欄位標題
	 *         'width' => 10,       //欄位寬度
	 *         'alignment' => [
	 *             'h' => 'center', //水平對齊: center=置中, right=置右, left=置左
	 *             'v' => 'top'     //垂直對齊: center=置中, top=置頂, bottom=置底
	 *         ],
     *         'rowspan' => 2, // 合併儲存格: 行數
     *         'colspan' => 5, // 合併儲存格: 欄數
     *         'merge' => [    // 合併儲存格的下方子欄位
     *             'sub_col1' => ['name' => '子欄位名稱1', 'width' => 5, ''],
     *             'sub_col2' => ['name' => '子欄位名稱2', 'width' => 5, ''],
     *         ]
	 *     ],
	 * ]
	 *
	 * 資料列
	 * $data_rows = [
	 *     ['target_no' => 'STN2021091300001', 'user_id' => '47174'],
	 *     ['target_no' => 'STS2019061700001', 'user_id' => '487']
	 *     ['target_no' => 'STS2019061700001', 'user_id' => ['value' => '123456', 'rowspan' => 2]]
	 * ]
	 */
	function load($title_rows, $data_rows, Spreadsheet $spreadsheet = NULL, $sheet_title = '')
	{
        if ( ! isset($spreadsheet))
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setTitle($sheet_title);
        }
        else
        {
            $this->initial_row_index();
            $sheet_count = $spreadsheet->getSheetCount();
            $new_work_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheet_title);
            $sheet = $spreadsheet->addSheet($new_work_sheet, $sheet_count);
        }

        $style = $spreadsheet->getDefaultStyle();
        $style->getFont()->setName('微軟正黑體');
		$style->getFont()->setSize(12);
		$style->getAlignment()->setWrapText(true);
		$style->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $this->draw_title($sheet, $title_rows, $this->row_index);
        $this->draw_data($sheet, $title_rows, $data_rows, $this->row_index);

        return $spreadsheet;
	}

    private function initial_row_index()
    {
        $this->row_index = 1;
    }

    function save($filename, $spreadsheet)
    {
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filename);
    }

    function download($filename, $spreadsheet) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename={$filename}");
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
     * @param int $column_counter
     * @return void
     */
	function draw_title(&$sheet, $title_rows, int $row_index, int $column_counter = 0)
	{
        $max_row_index = $row_index;
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

            // rowspan
            $row_span = 1;
            if ( ! empty($value['rowspan']))
            {
                $row_span = $value['rowspan'];
                $merge_end_row_index = $row_index + $row_span - 1;
                if ($merge_end_row_index > $max_row_index)
                {
                    $max_row_index = $merge_end_row_index;
                }
                $sheet->mergeCells("{$column_index}{$row_index}:{$column_index}{$merge_end_row_index}");
            }

            // colspan
            if ( ! empty($value['colspan']))
            {
                if ( ! empty($value['merge']))
                {
                    $this->draw_title($sheet, $value['merge'], ($row_index + $row_span), $column_counter);
                }
                $column_counter = $column_counter + $value['colspan'] - 1;
                $merge_end_column_index = $this->ASCII_chr($column_counter);
                $sheet->mergeCells("{$column_index}{$row_index}:{$merge_end_column_index}{$row_index}");
            }

			$column_counter++;
		}
        $this->row_index = $max_row_index + 1;
	}

	/**
	 * 畫資料
	 * @param $sheet
	 * @param $title_rows : 標題列
	 * @param $data_rows : 資料列
	 * @param int $row_index : 從第n列開始畫
	 * @return mixed
	 */
	function draw_data(&$sheet, $title_rows, $data_rows, int $row_index = 2)
	{
		foreach ($data_rows as $this_data_row) {
			$column_counter = 0;
            foreach ($title_rows as $this_title_key => $this_title_row)
            {
                $this->draw_each_data_row(
                    $sheet,
                    [$this_title_key => $this_title_row],
                    $this_data_row,
                    $row_index,
                    $column_counter
                );
            }
            $row_index++;
		}
	}

    /**
     * @return void
     */
    public function draw_each_data_row(&$sheet, $this_title_row, $this_data_row, int &$row_index, int &$column_counter = 0)
    {
        $key = array_key_first($this_title_row);
        $this_title_row = current($this_title_row);

        $column_index = $this->ASCII_chr($column_counter);
        if ( ! empty($this_title_row['merge']))
        {
            foreach ($this_title_row['merge'] as $sub_title_key => $sub_title_row)
            {
                $this->draw_each_data_row($sheet, [$sub_title_key => $sub_title_row], $this_data_row, $row_index, $column_counter);
            }
            return;
        }
        $data_value = '';
        if (isset($this_data_row[$key]))
        {
            if(is_array($this_data_row[$key]) && isset($this_data_row[$key]['value']))
            {
                $data_value = $this_data_row[$key]['value'];
                if ( ! empty($this_data_row[$key]['rowspan']))
                {
                    $merge_end_row_index = $row_index + $this_data_row[$key]['rowspan'] - 1;
                    $sheet->mergeCells("{$column_index}{$row_index}:{$column_index}{$merge_end_row_index}");
                }
            }
            else
            {
                $data_value = $this_data_row[$key];
            }
        }

        $data_type = $this_title_row['datatype'] ?? (is_numeric($data_value) ? DataType::TYPE_NUMERIC : DataType::TYPE_STRING);
        $sheet->setCellValueExplicit($column_index . $row_index, $data_value, $data_type);

        if (isset($this_title_row['alignment']))
        {
            $alignment = $sheet->getStyle($column_index . $row_index)->getAlignment();

            //水平對齊
            if (isset($this_title_row['alignment']['h']))
            {
                switch ($this_title_row['alignment']['h'])
                {
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
            if (isset($this_title_row['alignment']['v']))
            {
                switch ($this_title_row['alignment']['v'])
                {
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

    function get_investor_report_from_html($html, $data) {
        $html = str_replace('&', '&amp;', $html);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($html);
        $spreadsheet->getProperties()
            ->setCreator("普匯金融科技股份有限公司")
            ->setLastModifiedBy("普匯金融科技股份有限公司")
            ->setTitle("投資人報告書")
            ->setSubject("投資人報告書")
            ->setDescription("普匯金融科技股份有限公司-投資人報告書")
            ->setKeywords("普匯金融科技股份有限公司 投資人報告書")
            ->setCategory("投資人報告書")
            ->setCompany('普匯金融科技股份有限公司');

        $width_list = [['column' => 'A', 'width' => 18.33], ['column' => 'B', 'width' => 11.17], ['column' => 'C', 'width' => 8.5],
            ['column' => 'D', 'width' => 11.17], ['column' => 'E', 'width' => 12.3], ['column' => 'F', 'width' => 6.67],
            ['column' => 'G', 'width' => 6.67], ['column' => 'H', 'width' => 8.5], ['column' => 'I', 'width' => 7.3],
            ['column' => 'J', 'width' => 12]];
        foreach ($width_list as $info)
        {
            $spreadsheet->getActiveSheet()->getStyle($info['column'])->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle($info['column'])->getAlignment()->setVertical('center');
            $spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->setAutoSize(TRUE);
        }

        $alignments = [
            [
                'range' => 'A'.($data['start_row']['account_payable_interest']-5).':G'.($data['start_row']['account_payable_interest']-3),
                'horizontal' => 'left'
            ]
        ];
        $this->set_alignment($spreadsheet, $alignments);

        $format_list = [
            [
                'range' => 'D1',
                'format_code' => '#,##0'
            ],
            [
                'range' => 'B6:D8',
                'format_code' => '#,##0'
            ],
            [
                'range' => 'B'.($data['start_row']['realized_rate_of_return']+1).':I'.($data['start_row']['realized_rate_of_return']+count($data['realized_rate_of_return']??[])),
                'format_code' => '#,##0'
            ],
            [
                'range' => 'B'.($data['start_row']['account_payable_interest']+1).':B'.($data['start_row']['delay_not_return']-3),
                'format_code' => '#,##0'
            ],
            [
                'range' => 'B'.($data['start_row']['delay_not_return']+1).':B'.($data['start_row']['delay_not_return']+count($data['delay_not_return']??[])),
                'format_code' => '#,##0'
            ],
            [
                'range' => 'J'.($data['start_row']['realized_rate_of_return']+1).':J'.($data['start_row']['realized_rate_of_return']+count($data['realized_rate_of_return']??[])),
                'format_code' => NumberFormat::FORMAT_PERCENTAGE_00
            ]
        ];
        $this->set_format_code($spreadsheet, $format_list);

        $border_list = [
            [
                'range' => 'A1:D2',
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ],
                    'outline' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ]

                ]
            ],
            [
                'range' => 'A5:D8',
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ]
                ]
            ],
            [
                'range' => 'A11:J'.($data['start_row']['realized_rate_of_return']+count($data['realized_rate_of_return']??[])),
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ]
                ]
            ],
            [
                'range' => 'A'.$data['start_row']['account_payable_interest'].':B'.($data['start_row']['delay_not_return']-3),
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ]
                ]
            ],
            [
                'range' => 'A'.$data['start_row']['delay_not_return'].':B'.($data['start_row']['delay_not_return']+count($data['delay_not_return']??[])),
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFFF'],
                    ]
                ]
            ]
        ];
        $this->set_border($spreadsheet, $border_list);

        // 針對低於最小寬度的欄位重新調整
        $spreadsheet->getActiveSheet()->calculateColumnWidths();
        foreach ($width_list as $info) {
            if($spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->getWidth() < $info['width'])
            {
                $spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->setAutoSize(FALSE);
                $spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->setWidth($info['width']);
            }
        }

        $spreadsheet->getActiveSheet()->setSelectedCell('A1');
        return $spreadsheet;
    }

    function set_alignment(&$spreadsheet, $alignments)
    {
        foreach ($alignments as $alignment)
        {
            if(isset($alignment['vertical']))
            {
                $spreadsheet->getActiveSheet()->getStyle($alignment['range'])->getAlignment()->setVertical($alignment['vertical']);
            }
            if(isset($alignment['horizontal']))
            {
                $spreadsheet->getActiveSheet()->getStyle($alignment['range'])->getAlignment()->setHorizontal($alignment['horizontal']);
            }
        }
        return $spreadsheet;
    }

    function set_format_code(&$spreadsheet, $format_list)
    {
        foreach ($format_list as $format)
        {
            if (isset($format['format_code']))
            {
                $spreadsheet->getActiveSheet()->getStyle($format['range'])->getNumberFormat()->setFormatCode($format['format_code']);

            }
        }
        return $spreadsheet;
    }

    function set_border(&$spreadsheet, $borders) {
        foreach ($borders as $border)
        {
            if (isset($border['range']))
            {
                $spreadsheet->getActiveSheet()->getStyle($border['range'])->applyFromArray(['borders' => $border['borders']]);
            }
        }
        return $spreadsheet;
    }
}
