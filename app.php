<?php

    function __autoload($className) {
        require_once $className . '.php';
    }

    do {
        printCommands();
        $command = trim(fgets(STDIN));
        $controller = new StaffController();
        switch ($command) {
            case 'register':
                RegisterStaff();
                break;
            case 'delete':
                deleteStaff();
                break;
            case 'find':
                findStaff();
                break;
            case 'import':
                importStaff();
                break;
            case 'quit':
                exit(0);
                break;
            default:
                fwrite(STDOUT, "command '$command' doesnt't exist\n");
        }

        fwrite(STDOUT, "\npress enter to continue\n");
        fgets(STDIN);

    } while ( $command != 'quit' );

    function printCommands()
    {
        $actions = [
            'register' => 'to register a new staff',
            'delete' => 'to delete staff',
            'find' => 'to find staff',
            'import' => 'to import staff from csv file',
            'quit' => 'to quit the application',
        ];

        foreach ($actions as $action => $description) {
            fwrite(STDOUT, "type '$action' to: $description \n");
        }
        fwrite(STDOUT, "\ncommand:");
    }

    function RegisterStaff()
    {
        fwrite(STDOUT, "\nfirstname:");
        $params['firstName'] = trim(fgets(STDIN));
        fwrite(STDOUT, "\nlastname:");
        $params['lastName'] = trim(fgets(STDIN));
        fwrite(STDOUT, "\nemail:");
        $params['email'] = trim(fgets(STDIN));
        fwrite(STDOUT, "\nphonenumber1:");
        $params['phoneNumber1'] = trim(fgets(STDIN));
        fwrite(STDOUT, "\nphonenumber2:");
        $params['phoneNumber2'] = trim(fgets(STDIN));
        fwrite(STDOUT, "\ncomment:");
        $params['comment'] = trim(fgets(STDIN));

        $staffController = new StaffController();
        $staffController->registerStaff($params);
    }

    function findStaff()
    {
        fwrite(STDOUT, "\nsearch:");
        $searchString = trim(fgets(STDIN));

        $staffController = new StaffController();
        $staffController->findStaff($searchString);
    }

    function deleteStaff()
    {
        fwrite(STDOUT, "\nsearch staff to delete:");
        $email = trim(fgets(STDIN));

        $staffController = new StaffController();
        $staffController->deleteStaff($email);
    }

    function importStaff()
    {
        fwrite(STDOUT, "\nspecify absolute file path, example:\n");
        fwrite(STDOUT, '"C:\Documents\staff.csv"');
        fwrite(STDOUT, "\nenter file location:\n");
        $filePath = trim(fgets(STDIN));

        $staffController = new StaffController();
        $staffController->importStaffFromCsv($filePath);
    }
?>