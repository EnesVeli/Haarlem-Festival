<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class InvoiceRepository extends Repository
{
    public function createInvoice(int $orderId, float $total, float $vatPct, string $clientName, string $clientEmail): int
    {
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);

        $stmt = $this->connection->prepare(
            "INSERT INTO `Invoice` (order_id, invoice_number, total_amount, vat_percentage, client_name, client_address)
             VALUES (:oid, :num, :total, :vat, :name, :email)"
        );
        $stmt->execute([
            ':oid'   => $orderId,
            ':num'   => $invoiceNumber,
            ':total' => $total,
            ':vat'   => $vatPct,
            ':name'  => $clientName,
            ':email' => $clientEmail,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function getByOrder(int $orderId): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM `Invoice` WHERE order_id = :oid LIMIT 1"
        );
        $stmt->execute([':oid' => $orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}