<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Freekefu extends ORM {

    public function deleteClear()
    {
        $this->delete();
    }
}