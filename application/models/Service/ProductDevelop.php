<?php
class Service_ProductDevelop extends Common_Service
{
    /**
     * @var null
     */
    public static $_modelClass = null;

    /**
     * @return Table_ProductDevelop|null
     */
    public static function getModelInstance()
    {
        if (is_null(self::$_modelClass)) {
            self::$_modelClass = new Table_ProductDevelop();
        }
        return self::$_modelClass;
    }

    /**
     * @param $row
     * @return mixed
     */
    public static function add($row)
    {
        $model = self::getModelInstance();
        return $model->add($row);
    }


    /**
     * @param $row
     * @param $value
     * @param string $field
     * @return mixed
     */
    public static function update($row, $value, $field = "pd_id")
    {
        $model = self::getModelInstance();
        return $model->update($row, $value, $field);
    }

    /**
     * @param $value
     * @param string $field
     * @return mixed
     */
    public static function delete($value, $field = "pd_id")
    {
        $model = self::getModelInstance();
        return $model->delete($value, $field);
    }

    /**
     * @param $value
     * @param string $field
     * @param string $colums
     * @return mixed
     */
    public static function getByField($value, $field = 'pd_id', $colums = "*")
    {
        $model = self::getModelInstance();
        return $model->getByField($value, $field, $colums);
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        $model = self::getModelInstance();
        return $model->getAll();
    }

    /**
     * @param array $condition
     * @param string $type
     * @param int $pageSize
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public static function getByCondition($condition = array(), $type = '*', $pageSize = 0, $page = 1, $order = "")
    {
        $model = self::getModelInstance();
        return $model->getByCondition($condition, $type, $pageSize, $page, $order);
    }
    
    public static function getDevelopingCount(){
    	$model = self::getModelInstance();
    	return $model->getDevelopingCount();
    }
    
    public static function getDevelopedCount(){
    	$model = self::getModelInstance();
    	return $model->getDevelopedCount();
    }

    /**
     * @param $val
     * @return array
     */
    public static function validator($val)
    {
        $validateArr = $error = array();
        
        return  Common_Validator::formValidator($validateArr);
    }
    
    /**
     * @param array $params
     * @return array
     */
    public  function getFields()
    {
        $row = array(
        
              'E0'=>'pd_id',
              'E1'=>'product_id',
              'E2'=>'product_title',
              'E3'=>'product_title_en',
              'E4'=>'product_sku',
              'E5'=>'product_category_ebay',
              'E6'=>'product_category',
              'E7'=>'es_id',
              'E8'=>'pd_weight',
              'E9'=>'pd_height',
              'E10'=>'pd_width',
              'E11'=>'pd_length',
              'E12'=>'pd_type',
              'E13'=>'pd_reason',
              'E14'=>'pd_status',
              'E15'=>'is_competitor',
              'E16'=>'pd_phototype',
        );
        return $row;
    }

}