<?php

interface iStaffRepository
{
    public function add(Staff $user);
    public function find($searchString);
    public function delete($searchString);
}

class StaffRepository implements iStaffRepository
{
    const fileName = 'data.csv';

    const header = ['firstName', 'lastName', 'email', 'phoneNumber1', 'phoneNumber2', 'comment'];

    private function getFile()
    {
        if (file_exists(self::fileName)) {
            $file = fopen(self::fileName, "r");
            if (fgetcsv($file) === self::header) {     //check if header matches the current file
                return self::fileName;
            }
        }
        return $this->createNewFile();
    }

    private function createNewFile ()
    {
        $csvFile = fopen(self::fileName, "w");
        fputcsv($csvFile, self::header);
        fclose($csvFile);

        return self::fileName;
    }

    public function add(Staff $staff)
    {
        $data = $staff->getObjectAsArray();

        $csvFile = fopen(self::getFile(), 'a') or die("Unable to open file!");
        fputcsv($csvFile, $data);
        fclose($csvFile);

        return $staff;
    }

    public function find($searchString)
    {
        $staffArray = [];

        $csvFile = fopen(self::getFile(), 'r') or die("Unable to open file!");

        do {
            $row = fgetcsv($csvFile);
            if ($row) {
                if (strpos(implode(' ', $row), $searchString) !== false) { //convert staff row to string and look for search string in it
                    $staffAssociativeArray = array_combine(self::header, $row);
                    $staff = new Staff();
                    foreach ($staffAssociativeArray as $key => $value) {
                        $setter = 'set' . ucfirst($key);
                        $staff->{$setter}($value);
                    }
                    $staffArray[] = $staff;
                }
            }
        } while (!feof ($csvFile));

        fclose($csvFile);

        return $staffArray;
    }

    public function delete($email = '')  //delete creates a new file and copies all rows except one where email matches
    {
        $currentFileName = self::fileName;
        $tempFileName =  'temp' . $currentFileName;

        if (file_exists($tempFileName)) unlink($tempFileName);

        $newFile = fopen($tempFileName, "w");
        $csvFile = fopen(self::getFile(), 'r') or die("Unable to open file!");

        do {
            $row = fgetcsv($csvFile);
            if ($row) {
                $staff = array_combine(self::header, $row);
                if (isset($staff['email']) && $staff['email'] !== $email) {
                    fputcsv($newFile, $row);
                }
            }
        } while (!feof ($csvFile));

        fclose($csvFile);
        fclose($newFile);

        unlink($currentFileName);
        rename($tempFileName,$currentFileName);
    }

    public static function update()
    {
        //@todo add update method
    }
}