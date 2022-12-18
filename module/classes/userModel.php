<?php

namespace Module\Classes;

class userModel {
    public int $id, $role, $status;
    public string $username, $email, $password, $picture;
    
    public function __construct(array $user) {
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->role = $user['role'];
        $this->status = $user['status'];
        $this->picture = $user['picture'] || "";

    }
}

class userRole {
    public static array $roleList = ["Pengguna" => 10, "Editor" => 20, "Developer" => 30, "Admin" => 40];
    public static array $roleKeyList = [10 => "Pengguna", 20 => "Editor", 30 => "Developer", 40 => "Admin"];

}