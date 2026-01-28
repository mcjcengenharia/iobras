-- iObras PRD v0.7 (Módulos 1–7)
-- MySQL 8 / PHP 8.1 / Apache

SET NAMES utf8mb4;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id BIGINT UNSIGNED NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_role (role_id),
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_user (user_id),
  INDEX idx_audit_action (action),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clients (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  document VARCHAR(30) NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(30) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_clients_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contracts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_id BIGINT UNSIGNED NOT NULL,
  obra_nome VARCHAR(200) NOT NULL,
  start_date DATE NULL,
  end_date DATE NULL,
  contract_value DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  status ENUM('Ativo','Pausado','Encerrado') NOT NULL DEFAULT 'Ativo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_contracts_client (client_id),
  INDEX idx_contracts_status (status),
  CONSTRAINT fk_contracts_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cost_categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS work_costs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  contract_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  cost_date DATE NOT NULL,
  description VARCHAR(255) NOT NULL,
  amount DECIMAL(15,2) NOT NULL,
  status ENUM('Previsto','Aprovado','Pago') NOT NULL DEFAULT 'Previsto',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_wc_contract (contract_id),
  INDEX idx_wc_category (category_id),
  INDEX idx_wc_status (status),
  INDEX idx_wc_date (cost_date),
  CONSTRAINT fk_wc_contract FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE,
  CONSTRAINT fk_wc_category FOREIGN KEY (category_id) REFERENCES cost_categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS measurements (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  contract_id BIGINT UNSIGNED NOT NULL,
  reference_month DATE NOT NULL,
  description VARCHAR(255) NOT NULL,
  gross_amount DECIMAL(15,2) NOT NULL,
  approved_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  status ENUM('Prevista','Aprovada','Faturada','Recebida') NOT NULL DEFAULT 'Prevista',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_meas_contract (contract_id),
  INDEX idx_meas_status (status),
  INDEX idx_meas_ref (reference_month),
  CONSTRAINT fk_meas_contract FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP VIEW IF EXISTS vw_receita_contrato;
CREATE VIEW vw_receita_contrato AS
SELECT m.contract_id, SUM(m.approved_amount) AS receita
FROM measurements m
WHERE m.status IN ('Aprovada','Faturada','Recebida')
GROUP BY m.contract_id;

DROP VIEW IF EXISTS vw_despesa_contrato;
CREATE VIEW vw_despesa_contrato AS
SELECT wc.contract_id, SUM(wc.amount) AS despesa
FROM work_costs wc
WHERE wc.status IN ('Aprovado','Pago')
GROUP BY wc.contract_id;

DROP VIEW IF EXISTS vw_resultado_contrato;
CREATE VIEW vw_resultado_contrato AS
SELECT
  c.id AS contract_id,
  c.obra_nome,
  cl.name AS cliente,
  COALESCE(r.receita,0) AS receita,
  COALESCE(d.despesa,0) AS despesa,
  (COALESCE(r.receita,0) - COALESCE(d.despesa,0)) AS resultado
FROM contracts c
JOIN clients cl ON cl.id = c.client_id
LEFT JOIN vw_receita_contrato r ON r.contract_id = c.id
LEFT JOIN vw_despesa_contrato d ON d.contract_id = c.id;

INSERT IGNORE INTO roles (id, name) VALUES
  (1,'Administrador'),
  (2,'Gerente'),
  (3,'Financeiro'),
  (4,'Leitor');

SET FOREIGN_KEY_CHECKS = 1;
