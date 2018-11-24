<?php

include_once 'Staff.php';
include_once 'StaffRepository.php';

class StaffController
{
    public static function registerStaff($params = [])
    {
        if (empty($params)) {
            fwrite(STDOUT, "no parameters entered\n");
        }

        if(self::validateEmail($params['email'])) {
            $staff = new Staff();
            if(isset($params['firstName'])) $staff->setFirstName(trim($params['firstName']));
            if(isset($params['lastName'])) $staff->setLastName(trim($params['lastName']));
            if(isset($params['email'])) $staff->setEmail(trim($params['email']));
            if(isset($params['phoneNumber1'])) $staff->setPhoneNumber1(trim($params['phoneNumber1']));
            if(isset($params['phoneNumber2'])) $staff->setPhoneNumber2(trim($params['phoneNumber2']));
            if(isset($params['comment'])) $staff->setComment(trim($params['comment']));

            $staffRepository = new StaffRepository();
            $staffRepository->add($staff);

            fwrite(STDOUT, "\nsuccesfully added: \n");
            fwrite(STDOUT, $staff);

            return $staff;
        };
    }

    public static function findStaff($searchString = '')
    {
        $staffArray = [];
        if ($searchString) {
            $staffRepository = new StaffRepository();
            $staffArray = $staffRepository->find($searchString);

            if ($staffArray) {
                fwrite(STDOUT, "staff that matches search string '$searchString':" . count($staffArray) . "\n");
                foreach ($staffArray as $staff) {
                    fwrite(STDOUT, $staff);
                }
            } else {
                fwrite(STDOUT, " no staff found matches search string '$searchString'\n");
            }
        } else {
            fwrite(STDOUT, "no search value entered");
        }

        return $staffArray;
    }

    public static function deleteStaff($searchString = '')
    {
        if ($searchString) {
            $staffRepository = new StaffRepository();

            $staffArray = $staffRepository->find($searchString);
            if ($staffArray) {
                foreach ($staffArray as $staff) {
                    fwrite(STDOUT, "delete this entry?\n");
                    fwrite(STDOUT, $staff);
                    fwrite(STDOUT, "Are you sure you want to do this?  Type 'yes' to continue:");
                    $input = trim(fgets(STDIN));
                    if ($input == 'yes') {
                        $staffRepository->delete($staff->getEmail());
                        fwrite(STDOUT, "successfully deleted\n");
                    } else {
                        fwrite(STDOUT, "skipping entry\n");
                    }
                }
            } else {
                fwrite(STDOUT, "no staff found matches:'$searchString'\n");
            }
        }
    }

    public static function importStaffFromCsv($filePath = '')
    {
        //@todo selected which column represents which column
        //@todo csv file needs to match header

        $staffRepository = new StaffRepository();

        if (file_exists($filePath) && pathinfo($filePath)['extension'] == 'csv') {
            $file = fopen($filePath, "r");
            $firstRow = fgetcsv($file);
            if (StaffRepository::header === $firstRow) {
                do {
                    $row = fgetcsv($file);
                    if ($row && count(StaffRepository::header) == count($row)) {
                        $staffAssociativeArray = array_combine(StaffRepository::header, $row);
                        $staff = new Staff();
                        foreach ($staffAssociativeArray as $key => $value) {
                            $setter = 'set' . ucfirst($key);
                            $staff->{$setter}($value);
                        }
                        if (self::validateEmail($staff->getEmail())) {
                            $staffRepository->add($staff);
                        }
                    }
                } while (!feof ($file));
            }
            fclose($file);
        } else {
            fwrite(STDOUT, "file doesn't exist or is not a csv file\n");
        }
    }

    private function validateEmail($email)
    {
        $staffRepository = new StaffRepository();

        if (empty($email)) {
            fwrite(STDOUT, "no email entered\n");

            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            fwrite(STDOUT, "$email is not a valid email address\n");

            return false;
        } elseif (!empty($staffRepository->find($email))) {
            fwrite(STDOUT, "staff with this email already exists\n");

            return false;
        }

        return true;
    }

}