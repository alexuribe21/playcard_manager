<?php
// app/models/Tarjeta.php — Fix: agregado validateCodigo() y numeroExiste()

require_once __DIR__ . '/../core/Database.php';

class Tarjeta
{
    public static function all(string $q = ''): array {
        $db = Database::getInstance();
        $sql = "SELECT * FROM tbl_tarjetaclientes";
        if ($q !== '') {
            $sql .= " WHERE str_coditotarjeta LIKE :q";
            $stmt = $db->prepare($sql);
            $stmt->execute([':q' => "%$q%"]);
        } else {
            $stmt = $db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM tbl_tarjetaclientes WHERE id_tarjeta = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): bool {
        $db = Database::getInstance();
        $sql = "INSERT INTO tbl_tarjetaclientes 
                   (str_coditotarjeta, enum_estado, num_saldo, num_puntos, str_pin, date_fechavencimiento, txt_notas)
                VALUES (:codigo, :estado, :saldo, :puntos, :pin, :vencimiento, :notas)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':codigo'       => $data['str_coditotarjeta'] ?? '',
            ':estado'       => $data['enum_estado'] ?? 'activa',
            ':saldo'        => $data['num_saldo'] ?? 0,
            ':puntos'       => $data['num_puntos'] ?? 0,
            ':pin'          => $data['str_pin'] ?? '',
            ':vencimiento'  => $data['date_fechavencimiento'] ?? null,
            ':notas'        => $data['txt_notas'] ?? ''
        ]);
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getInstance();
        $sql = "UPDATE tbl_tarjetaclientes
                   SET str_coditotarjeta     = :codigo,
                       enum_estado           = :estado,
                       num_saldo             = :saldo,
                       num_puntos            = :puntos,
                       str_pin               = :pin,
                       date_fechavencimiento = :vencimiento,
                       txt_notas             = :notas
                 WHERE id_tarjeta = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':codigo'       => $data['str_coditotarjeta'] ?? '',
            ':estado'       => $data['enum_estado'] ?? 'activa',
            ':saldo'        => $data['num_saldo'] ?? 0,
            ':puntos'       => $data['num_puntos'] ?? 0,
            ':pin'          => $data['str_pin'] ?? '',
            ':vencimiento'  => $data['date_fechavencimiento'] ?? null,
            ':notas'        => $data['txt_notas'] ?? '',
            ':id'           => $id
        ]);
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM tbl_tarjetaclientes WHERE id_tarjeta = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function recargar(int $id, float $monto): bool {
        $db = Database::getInstance();
        $sql = "UPDATE tbl_tarjetaclientes SET num_saldo = num_saldo + :monto WHERE id_tarjeta = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':monto' => $monto, ':id' => $id]);
    }

    public static function toggleEstado(int $id): bool {
        $db = Database::getInstance();
        $sql = "UPDATE tbl_tarjetaclientes
                   SET enum_estado = CASE WHEN enum_estado = 'activa' THEN 'bloqueada' ELSE 'activa' END
                 WHERE id_tarjeta = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // === Métodos de validación agregados ===
    public static function validateCodigo(string $codigo): bool {
        return $codigo !== '' && strlen($codigo) <= 50;
    }

    public static function numeroExiste(string $codigo, ?int $ignoreId = null): bool {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM tbl_tarjetaclientes WHERE str_coditotarjeta = :codigo";
        $params = [':codigo' => $codigo];
        if ($ignoreId) {
            $sql .= " AND id_tarjeta != :id";
            $params[':id'] = $ignoreId;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
