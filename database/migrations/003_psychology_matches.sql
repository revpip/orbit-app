-- ORBIT app migration 003

CREATE TABLE IF NOT EXISTS psychology_profiles (
  user_id BIGINT UNSIGNED PRIMARY KEY,
  communication_style VARCHAR(80) NULL,
  social_energy VARCHAR(80) NULL,
  conflict_style VARCHAR(80) NULL,
  humour_style VARCHAR(80) NULL,
  reliability_self_score TINYINT UNSIGNED NULL,
  openness_score TINYINT UNSIGNED NULL,
  boundaries_score TINYINT UNSIGNED NULL,
  raw_answers JSON NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_psych_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS matches (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_one_id BIGINT UNSIGNED NOT NULL,
  user_two_id BIGINT UNSIGNED NOT NULL,
  compatibility_score TINYINT UNSIGNED NOT NULL,
  reason_summary TEXT NULL,
  status ENUM('suggested','liked','mutual','dismissed','blocked') NOT NULL DEFAULT 'suggested',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_match_pair (user_one_id, user_two_id),
  CONSTRAINT fk_matches_one FOREIGN KEY (user_one_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_matches_two FOREIGN KEY (user_two_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
