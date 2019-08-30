<?php

namespace App\Repositories\Eloquent;

use App\Models\ItemTypePrice;
use App\Repositories\ItemTypePriceRepository;

class DBItemTypePriceRepository extends DBRepository implements ItemTypePriceRepository
{
    public const TRONG_HINH_TYPE = 1;
    public const DUONG_TRUONG_TYPE = 2;
    public const FOUR_HOUR_TYPE = 3;
    public const ONE_HOUR_TYPE = 4;

    public const TRONG_GIO_HANH_CHINH_TYPE = 1;
    public const NGOAI_GIO_HANH_CHINH_TYPE = 2;
    public const BUOI_SANG_CHIEU_TYPE = 3;
    public const BUOI_TOI_TYPE = 4;

    public const BUOI_SANG = 1;
    public const BUOI_CHIEU = 2;
    public const BUOI_TOI = 3;

    public function __construct(ItemTypePrice $model)
    {
        parent::__construct($model);
    }

    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }
    public function paginate($per = 10)
    {
        return $this->model::with('item_type')->orderBy('id', 'desc')->paginate($per);
    }

    /**
     * @return array
     */
    public static function listObjectPriceType(){
        $listTypeCodes = self::listPriceTypeCode();
        $object = [];
        foreach ($listTypeCodes as $key => $value){
            $object_item = new \stdClass();
            $object_item->id = $value;
            $object_item->text = self::getTextFromType($value);
            array_push($object, $object_item);
        }
        return $object;
    }

    /**
     * @return array
     */
    public static function listPriceTypeCode(){
        return [
            self::TRONG_HINH_TYPE,
            self::DUONG_TRUONG_TYPE,
            self::FOUR_HOUR_TYPE,
            self::ONE_HOUR_TYPE
        ];
    }

    /**
     * @param $type
     * @return string
     */
    public static function getTextFromPriceType($type){
        switch ($type){
            case self::TRONG_HINH_TYPE :
                return "Tập trong hình";
            case self::DUONG_TRUONG_TYPE :
                return "Tập đường trường";
            case self::FOUR_HOUR_TYPE :
                return "1 Buổi (4 giờ)";
            case self::ONE_HOUR_TYPE :
                return "1 giờ";
            default:
                return "Xe của trung tâm";
        }
    }

    /**
     * @param $type
     * @return string
     */
    public static function getTextFromTimeType($type){
        switch ($type){
            case self::TRONG_GIO_HANH_CHINH_TYPE :
                return "Trong giờ hành chính";
            case self::NGOAI_GIO_HANH_CHINH_TYPE :
                return "Ngoài giờ hành chính";
            case self::BUOI_SANG_CHIEU_TYPE :
                return "Ngoài giờ hành chính(buổi sáng/chiều)";
            case self::BUOI_TOI_TYPE :
                return "Ngoài giờ hành chính(buổi tối)";
            default:
                return "Xe của trung tâm";
        }
    }
}