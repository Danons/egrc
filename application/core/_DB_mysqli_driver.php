<?php
class _DB_mysqli_driver extends CI_DB_mysqli_driver
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    protected $field_data = array();


    public function field_data($table)
    {
        if ($this->field_data[$table])
            return $this->field_data[$table];

        if (($query = $this->query('SHOW COLUMNS FROM ' . $this->protect_identifiers($table, TRUE, NULL, FALSE))) === FALSE) {
            return FALSE;
        }
        $query = $query->result_object();

        $retval = array();
        for ($i = 0, $c = count($query); $i < $c; $i++) {
            $retval[$i]            = new stdClass();
            $retval[$i]->name        = $query[$i]->field;

            sscanf(
                $query[$i]->type,
                '%[a-z](%d)',
                $retval[$i]->type,
                $retval[$i]->max_length
            );

            $retval[$i]->default        = $query[$i]->default;
            $retval[$i]->primary_key    = (int) ($query[$i]->key === 'pri');
        }

        $this->field_data[$table] = $retval;

        return $retval;
    }
}
