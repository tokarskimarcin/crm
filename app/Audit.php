<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Audit extends Model
{
    protected $table = 'audit';
    public $timestamps = false;

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
