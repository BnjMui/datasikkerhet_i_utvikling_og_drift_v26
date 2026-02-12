<?php
class User {
    private string $id;
    public string $first_name;
    public string $last_name;
    public string $mail;
    public string $role;
    public string $password;

    public function __construct(string $id, string $first_name, string $last_name, string $mail, string $role, string $password)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->mail = $mail;
        $this->role = $role;
        $this->password = $password | "";
    }
}


# class UserNoId {
#     public string $first_name;
#     public string $last_name;
#     public string $mail;
#     public string $role;
#     public string $password;
# 
#     public function __construct(string $first_name, string $last_name, string $mail, string $role, string $password)
#     {
#         $this->first_name = $first_name;
#         $this->last_name = $last_name;
#         $this->mail = $mail;
#         $this->role = $role;
#         $this->password = $password;
#     }
# }
