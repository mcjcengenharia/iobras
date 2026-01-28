<?php

class ClientModel
{
    public static function all(): array
    {
        $db = DB::conn();
        return $db->query("SELECT * FROM clients ORDER BY name ASC")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $db = DB::conn();
        $st = $db->prepare("SELECT * FROM clients WHERE id = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $db = DB::conn();
        $st = $db->prepare("INSERT INTO clients (name, document, email, phone) VALUES (?,?,?,?)");
        $st->execute([
            trim($data['name'] ?? ''),
            trim($data['document'] ?? ''),
            trim($data['email'] ?? ''),
            trim($data['phone'] ?? ''),
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $db = DB::conn();
        $st = $db->prepare("UPDATE clients SET name=?, document=?, email=?, phone=? WHERE id=?");
        $st->execute([
            trim($data['name'] ?? ''),
            trim($data['document'] ?? ''),
            trim($data['email'] ?? ''),
            trim($data['phone'] ?? ''),
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $db = DB::conn();
        $st = $db->prepare("DELETE FROM clients WHERE id = ?");
        $st->execute([$id]);
    }
}
