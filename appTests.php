<?php

//@TODO setup test environment

function __autoload($class_name) {
    require_once 'StaffController.php';
}

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, 'assertHandler');

function assertHandler($file, $line, $code, $desc = null)
{
    fwrite(STDOUT, "\nAssertion failed at $file:$line: $code");
    if ($desc) {
        fwrite(STDOUT, ":$desc");
    }
}

$params['firstName'] = 'test';
$params['lastName'] = 'test';
$params['email'] = 'test@test.com';
$params['phoneNumber1'] = '999';
$params['phoneNumber2'] = '111';
$params['comment'] = 'xD';

$staffController = new StaffController();

assert($params === $staffController->registerStaff($params)->getObjectAsArray());

$foundStaff = $staffController->findStaff('test@test.com')[0]->getObjectAsArray();
assert($params === $foundStaff);

?>