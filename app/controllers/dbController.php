<?php


namespace MUSICAA\controllers;


use MUSICAA\models\Data;

class dbController extends AbstractController
{
    public function defaultAction()
    {
        $data = new Data();
        $data->createTable();
        $data->addToTable([
            ['id' => 'terms', 'data' => 'lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem'],
            ['id' => 'privacy', 'data' => 'lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem lorem posim abosem'],
        ]);
    }
}