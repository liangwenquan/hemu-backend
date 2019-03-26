<?php
/**
 * Created by PhpStorm.
 * User: monoceros
 * Date: 2018/12/12
 * Time: 15:41
 */

namespace App\Ship\Database\Mongo;


use Illuminate\Support\Facades\DB;

class MongoValidator
{
    const TAG = 'MongoValidator';

    /**
     * @var \MongoDB $db
     */
    private $db;

    public function __construct($connectName = 'mongodb')
    {
        $this->db = DB::connection($connectName)->getMongoDB();
    }

    /**
     * @param $cmdJson
     * @return \MongoDB\Driver\Cursor
     */
    public function handle($cmdJson)
    {
        return $this->db->command(json_decode($cmdJson));
    }
}
