<?php
return array (
  'title' => 
  array (
    'id' => 1,
    'moduleid' => 1,
    'field' => 'title',
    'name' => '标题',
    'tips' => '',
    'required' => 1,
    'minlength' => 1,
    'maxlength' => 80,
    'pattern' => '',
    'errormsg' => '标题必须为1-80个字符',
    'class' => '',
    'type' => 'title',
    'setup' => 'array (
  \'thumb\' => \'1\',
  \'style\' => \'1\',
  \'size\' => \'55\',
)',
    'ispost' => 1,
    'unpostgroup' => '',
    'listorder' => 2,
    'status' => 1,
    'issystem' => 1,
  ),
  'content' => 
  array (
    'id' => 6,
    'moduleid' => 1,
    'field' => 'content',
    'name' => '内容',
    'tips' => '',
    'required' => 1,
    'minlength' => 0,
    'maxlength' => 0,
    'pattern' => 'defaul',
    'errormsg' => '',
    'class' => 'content',
    'type' => 'editor',
    'setup' => 'array (
  \'edittype\' => \'UEditor\',
)',
    'ispost' => 0,
    'unpostgroup' => '',
    'listorder' => 3,
    'status' => 1,
    'issystem' => 0,
  ),
  'hits' => 
  array (
    'id' => 2,
    'moduleid' => 1,
    'field' => 'hits',
    'name' => '点击次数',
    'tips' => '',
    'required' => 0,
    'minlength' => 0,
    'maxlength' => 8,
    'pattern' => '',
    'errormsg' => '',
    'class' => '',
    'type' => 'number',
    'setup' => 'array (
  \'size\' => \'10\',
  \'numbertype\' => \'1\',
  \'decimaldigits\' => \'0\',
  \'default\' => \'0\',
)',
    'ispost' => 1,
    'unpostgroup' => '',
    'listorder' => 8,
    'status' => 0,
    'issystem' => 0,
  ),
  'createtime' => 
  array (
    'id' => 3,
    'moduleid' => 1,
    'field' => 'createtime',
    'name' => '发布时间',
    'tips' => '',
    'required' => 1,
    'minlength' => 0,
    'maxlength' => 0,
    'pattern' => 'date',
    'errormsg' => '',
    'class' => '',
    'type' => 'datetime',
    'setup' => '',
    'ispost' => 1,
    'unpostgroup' => '',
    'listorder' => 97,
    'status' => 1,
    'issystem' => 1,
  ),
  'status' => 
  array (
    'id' => 5,
    'moduleid' => 1,
    'field' => 'status',
    'name' => '状态',
    'tips' => '',
    'required' => 0,
    'minlength' => 0,
    'maxlength' => 0,
    'pattern' => '',
    'errormsg' => '',
    'class' => '',
    'type' => 'radio',
    'setup' => 'array (
  \'options\' => \'发布|1
定时发布|0\',
  \'fieldtype\' => \'tinyint\',
  \'numbertype\' => \'1\',
  \'labelwidth\' => \'75\',
  \'default\' => \'1\',
)',
    'ispost' => 0,
    'unpostgroup' => '',
    'listorder' => 98,
    'status' => 1,
    'issystem' => 1,
  ),
  'template' => 
  array (
    'id' => 4,
    'moduleid' => 1,
    'field' => 'template',
    'name' => '模板',
    'tips' => '',
    'required' => 0,
    'minlength' => 0,
    'maxlength' => 0,
    'pattern' => '',
    'errormsg' => '',
    'class' => '',
    'type' => 'template',
    'setup' => '',
    'ispost' => 1,
    'unpostgroup' => '',
    'listorder' => 99,
    'status' => 1,
    'issystem' => 1,
  ),
);
?>