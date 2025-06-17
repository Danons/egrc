<?php
class Template_messageModel extends _Model
{
    public $table = 'mt_message_template';
    public $pk = 'id_message';
    public $label = 'msg';
    function __construct()
    {
        parent::__construct();
    }
}
