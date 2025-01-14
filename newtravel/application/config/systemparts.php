<?php
return array
(
    Model_SystemParts::$coreSystemPartCode => array
    (
        array
        (
            'id' => '0',
            'name' => '内核系统',
            'version_path' => "/{$GLOBALS['cfg_backdir']}/application/data/version.php",
            'status' => '1'
        )
    ),
    Model_SystemParts::$pcSystemPartCode => array
    (
        array
        (
            'id' => '0',
            'name' => '4.X版（仅维护升级）',
            'version_path' => '/data/version.php',
            'status' => '1'
        ),
        array
        (
            'id' => '5',
            'name' => '5.X版（最新版）',
            'version_path' => '/v5/data/version.php',
            'status' => '1'
        )
    ),
    Model_SystemParts::$mobileSystemPartCode => array
    (
        array
        (
            'id' => '0',
            'name' => '4.X版（仅维护升级）',
            'version_path' => '/shouji/application/data/version.php',
            'status' => '1'
        ),
        array
        (
            'id' => '1',
            'name' => '5.X版（最新版）',
            'version_path' => '/phone/application/data/version.php',
            'status' => '1'
        )
    )
);