<?php

interface iStaffRepository
{
    public function add(Staff $user);
    public function find($searchString);
    public function delete($searchString);
}

class StaffRepository implements iStaffRepository
{
    const header = ['firstName', 'lastName', 'email', 'phoneNumber1', 'phoneNumber2', 'comment'];

    protected $fileName = 'staff.csv';

    private function getFile()
    {
        if (file_exists($this->fileName)) {
            $file = fopen($this->fileName, "r");
            if (fgetcsv($file) === self::header) {     //check if header matches the current file
                return $this->fileName;
            }
        }
        return $this->createNewFile();
    }

    private function createNewFile ()
    {
        $csvFile = fopen($this->fileName, "w");
        fputcsv($csvFile, self::header);
        fclose($csvFile);

        return $this->fileName;
    }

    public function add(Staff $staff)
    {
        $data = $staff->getObjectAsArray();

        $csvFile = fopen($this->getFile(), 'a') or die("Unable to open file!");
        fputcsv($csvFile, $data);
        fclose($csvFile);

        return $staff;
    }

    public function find($searchString)
    {
        $staffArray = [];

        $csvFile = fopen($this->getFile(), 'r') or die("Unable to open file!");

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
        $currentFileName = $this->getFile();
        $tempFileName =  'temp' . $currentFileName;

        if (file_exists($tempFileName)) unlink($tempFileName);

        $newFile = fopen($tempFileName, "w");
        $csvFile = fopen($currentFileName, 'r') or die("Unable to open file!");

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