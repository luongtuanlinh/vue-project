<?php

namespace App\Repositories\Eloquent;

use App\Models\ItemType;
use App\Repositories\ItemTypeRepository;

class DBItemTypeRepository extends DBRepository implements ItemTypeRepository
{

    public const CENTRAL_TYPE = 0;
    public const OUTSIDE_CENTRAL_TYPE = 1;
    public const PERSONAL_TYPE = 2;

    public function __construct(ItemType $model)
    {
        parent::__construct($model);
    }

    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * @return array
     */
    public static function listObjectType(){
        $listTypeCodes = self::listTypeCode();
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
    public static function listTypeCode(){
        return [
            self::CENTRAL_TYPE,
            self::OUTSIDE_CENTRAL_TYPE,
            self::PERSONAL_TYPE
        ];
    }

    /**
     * @param $type
     * @return string
     */
    public static function getTextFromType($type){
        switch ($type){
            case self::CENTRAL_TYPE :
                return "Xe của trung tâm";
            case self::OUTSIDE_CENTRAL_TYPE :
                return "Xe của cơ sở đào tạo khác";
            case self::PERSONAL_TYPE :
                return "Xe cá nhân";
            default:
                return "Xe của trung tâm";
        }
    }

}