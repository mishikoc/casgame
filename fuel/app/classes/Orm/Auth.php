<?php

namespace Orm;

use Fuel\Core\Inflector;
use OrmModel as Base;
use Config;

class Auth extends Base
{

    protected static $_connection = 'slave';

    protected static $_write_connection = 'master';

    protected static $_table_name = 'cas_game';

    protected static $_primary_key = ['id'];

    protected static $_properties = [
        'id' => [
            'data_type' => 'int',
            'label' => 'Id',
        ],
        'prizeNumber' => [
            'data_type' => 'int',
            'label' => 'ArticleId',
            'null' => true,
            'form' => [
                'type' => 'number',
            ],
        ],
        'firstLastName' => [
            'data_type' => 'varchar',
            'label' => 'ArticleNo',
            'null' => true,
            'form' => [
                'type' => 'text',
                'maxlength' => 255,
            ],
        ],
    ];

}
