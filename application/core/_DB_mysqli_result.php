<?php
class _DB_mysqli_result extends CI_DB_mysqli_result
{

    public function __construct(&$driver_object)
    {
        parent::__construct($driver_object);
    }

    protected function _fetch_assoc()
    {
        $row = $this->result_id->fetch_assoc();

        if (!$row)
            return $row;

        $ret = array();

        foreach ($row as $idkey => $value) {
            $ret[strtolower($idkey)] = trim($value);
        }

        return $ret;
    }

    protected function _fetch_object($class_name = 'stdClass')
    {
        $row = $this->result_id->fetch_object($class_name);

        if ($row) {
            $temp = array();
            foreach ($row as $idkey => $value) {
                $temp[$idkey] = trim($value);
            }

            foreach ($temp as $k => $v) {
                $row->{strtolower($k)} = $v;
                unset($row->{$k});
            }
        }

        if ($class_name === 'stdClass' or !$row) {
            return $row;
        }

        $class_name = new $class_name();
        foreach ($row as $idkey => $value) {
            $class_name->$idkey = $value;
        }

        return $class_name;
    }
}
