<?php
/**
 * Model de usuário
 */

class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && Security::verifyPassword($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function register($data) {
        $data['password'] = Security::hashPassword($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
}
