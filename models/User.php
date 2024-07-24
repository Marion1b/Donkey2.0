<?php

class User{
    private ? int $id=null;
    private string $admin = "USER";
    public function __construct(
        private string $last_name,
        private string $first_name,
        private string $email,
        private string $password
    )
    {
        
    }

    public function getId():? int{
        return $this->id;
    }

    public function setID(int $id):void{
        $this->id = $id;
    }

    public function getAdmin():string{
        return $this->admin;
    }

    public function setAdmin(string $admin):void{
        $this->admin = $admin;
    }

    /**
     * Get the value of last_name
     */
    public function getLastName(): string
    {
            return $this->last_name;
    }

    /**
     * Set the value of last_name
     */
    public function setLastName(string $last_name): void
    {
            $this->last_name = $last_name;
    }

    /**
     * Get the value of first_name
     */
    public function getFirstName(): string
    {
            return $this->first_name;
    }

    /**
     * Set the value of first_name
     */
    public function setFirstName(string $first_name): void
    {
            $this->first_name = $first_name;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
            return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): void
    {
            $this->email = $email;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
            return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): void
    {
            $this->password = $password;
    }
}