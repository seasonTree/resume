<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 11:42
 */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Excel{
    public function exportExcel($data,$title){
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
        ini_set('max_execution_time','0');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($title);
        //从A开始
        $startLetter = 'A';
        $rowNumber =1;
        //遍历表头
        foreach ($data['cols'] as $k=>$col){
            $sheet->setCellValue($startLetter++.$rowNumber,$col);
        }
        ++$rowNumber;
        //遍历数据
        foreach ($data['rows'] as $k=>$row){
            $startLetter ='A';
            foreach ($row as $k =>$v){
                $sheet->setCellValue($startLetter++.$rowNumber,$v);
            }
            ++$rowNumber;
        }

        $this->autoFitColumnWidthToContent($sheet,'A','');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function autoFitColumnWidthToContent($sheet, $fromCol, $toCol){
        if (empty($toCol) ) {//not defined the last column, set it the max one
            $toCol = $sheet->getColumnDimension($sheet->getHighestColumn())->getColumnIndex();
        }
        for($i = $fromCol; $i <= $toCol; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }
        $sheet->calculateColumnWidths();
    }
}