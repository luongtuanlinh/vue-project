<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Maatwebsite\Excel\Sheet;
use \Maatwebsite\Excel\Writer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = [
            'UserLogin',
            'User',
            'ItemClass',
            'ItemType',
            'ItemTypePrice',
            'Item',
            'Course',
            'Order',
            'Program',
            'Source',
            'Student',
            'Exam',
            'Fee',
            'Ticket',
            'Customer',
            'Purchase',
            'ItemBlock',
            'Holiday'
        ];
        foreach($models as $model)
            app()->bind('App\Repositories\\'.$model.'Repository',
                'App\Repositories\Eloquent\DB'.$model.'Repository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadAssets();
        $this->loadExcelStyle();

    }

    public function loadAssets()
    {
        $theme = config('theme');
        $assets = $theme['assets'];

        view()->share('cssFiles', $assets['css']);
        view()->share('jsFiles', $assets['js']);
        view()->share('theme_skin', $theme['theme-skin']);
    }

    public function loadExcelStyle(){
        Writer::macro('setCreator', function (Writer $writer, string $creator) {
            $writer->getDelegate()->getProperties()->setCreator($creator);
        });
        Sheet::macro('setOrientation', function (Sheet $sheet, $orientation) {
            $sheet->getDelegate()->getPageSetup()->setOrientation($orientation);
        });
        /**
         * Cell macros
         */
        Writer::macro('setCellValue', function (Writer $writer, string $cell, string $data) {
            $writer->getDelegate()->setCellValue($cell, $data);
        });
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        Sheet::macro('horizontalAlign', function (Sheet $sheet, string $cellRange, string $align) {
            $sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal($align);
        });
        Sheet::macro('verticalAlign', function (Sheet $sheet, string $cellRange, string $align) {
            $sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setVertical($align);
        });
        Sheet::macro('wrapText', function (Sheet $sheet, string $cellRange) {
            $sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
        });
        Sheet::macro('mergeCells', function (Sheet $sheet, string $cellRange) {
            $sheet->getDelegate()->mergeCells($cellRange);
        });
        Sheet::macro('columnWidth', function (Sheet $sheet, string $column, float $width) {
            $sheet->getDelegate()->getColumnDimension($column)->setWidth($width);
        });
        Sheet::macro('rowHeight', function (Sheet $sheet, string $row, float $height) {
            $sheet->getDelegate()->getRowDimension($row)->setRowHeight($height);
        });
        Sheet::macro('setFontFamily', function (Sheet $sheet, string $cellRange, string $font) {
            $sheet->getDelegate()->getStyle($cellRange)->getFont()->setName($font);
        });
        Sheet::macro('setFontSize', function (Sheet $sheet, string $cellRange, float $size) {
            $sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize($size);
        });
        Sheet::macro('textRotation', function (Sheet $sheet, string $cellRange, int $degrees) {
            $sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setTextRotation($degrees);
        });
    }

}
