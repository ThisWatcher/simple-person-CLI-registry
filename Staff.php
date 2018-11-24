<?php

class Staff
{
    protected $firstName;

    protected $lastName;

    protected $email;

    protected $phoneNumber1;

    protected $phoneNumber2;

    protected $comment;

    public function __toString()
    {
       return (string) implode(',', get_object_vars($this)) . "\n";
    }

    public function getObjectAsArray()
    {
        return get_object_vars($this);
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhoneNumber1()
    {
        return $this->phoneNumber1;
    }

    public function setPhoneNumber1($phoneNumber1)
    {
        $this->phoneNumber1 = $phoneNumber1;
    }

    public function getPhoneNumber2()
    {
        return $this->phoneNumber2;
    }

    public function setPhoneNumber2($phoneNumber2)
    {
        $this->phoneNumber2 = $phoneNumber2;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}