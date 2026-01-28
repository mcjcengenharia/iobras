<?php

class ContractModel
{
    public static function all(): array
    {
        $db = DB::conn();
        return $db->query("
            SELECT c.*, cl.name AS client_name
            FROM contracts c
            JOIN clients cl ON cl.id = c.client_id
            ORDER BY c.id DESC
        ")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $db = DB::conn();
        $st = $db->prepare("SELECT * FROM contracts WHERE id = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $db = DB::conn();
        $st = $db->prepare("
            INSERT INTO contracts (client_id, obra_nome, start_date, end_date, contract_value, status)
            VALUES (?,?,?,?,?,?)
        ");
        $st->execute([
            (int)($data['client_id'] ?? 0),
            trim($data['obra_nome'] ?? ''),
            ($data['start_date'] ?? null) ?: null,
            ($data['end_date'] ?? null) ?: null,
            (float)($data['contract_value'] ?? 0),
            $data['status'] ?? 'Ativo',
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $db = DB::conn();
        $st = $db->prepare("
            UPDATE contracts
            SET client_id=?, obra_nome=?, start_date=?, end_date=?, contract_value=?, status=?
            WHERE id=?
        ");
        $st->execute([
            (int)($data['client_id'] ?? 0),
            trim($data['obra_nome'] ?? ''),
            ($data['start_date'] ?? null) ?: null,
            ($data['end_date'] ?? null) ?: null,
            (float)($data['contract_value'] ?? 0),
            $data['status'] ?? 'Ativo',
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $db = DB::conn();
        $st = $db->prepare("DELETE FROM contracts WHERE id = ?");
        $st->execute([$id]);
    }
}
