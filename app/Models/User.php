<?php

class UserModel
{
    public static function findByEmail(string $email): ?array
    {
        $db = DB::conn();
        $st = $db->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.email = ? AND u.is_active = 1
            LIMIT 1
        ");
        $st->execute([$email]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function seedIfEmpty(): void
    {
        $db = DB::conn();
        $count = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($count > 0) return;

        $hash = password_hash('admin123', PASSWORD_BCRYPT);

        $st = $db->prepare("INSERT INTO users (name,email,password_hash,role_id) VALUES (?,?,?,?)");
        $st->execute(['Admin','admin@iobras.local',$hash,1]);
        $st->execute(['Gerente','gerente@iobras.local',$hash,2]);
        $st->execute(['Financeiro','financeiro@iobras.local',$hash,3]);
        $st->execute(['Leitor','leitor@iobras.local',$hash,4]);
    }
}
