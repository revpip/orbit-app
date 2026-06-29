-- ORBIT app migration 002

CREATE TABLE IF NOT EXISTS intents (
  id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(80) NOT NULL UNIQUE,
  label VARCHAR(120) NOT NULL,
  category ENUM('friendship','dating','travel','support','skills','wellbeing','social') NOT NULL,
  requires_extra_privacy TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_intents (
  user_id BIGINT UNSIGNED NOT NULL,
  intent_id SMALLINT UNSIGNED NOT NULL,
  visibility ENUM('public','private','mutual_only') NOT NULL DEFAULT 'public',
  priority TINYINT UNSIGNED NOT NULL DEFAULT 3,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, intent_id),
  CONSTRAINT fk_user_intents_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_user_intents_intent FOREIGN KEY (intent_id) REFERENCES intents(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO intents (slug, label, category, requires_extra_privacy, is_active) VALUES
('local-friendship', 'Local friendship', 'friendship', 0, 1),
('coffee-chats', 'Coffee chats', 'social', 0, 1),
('days-out', 'Days out', 'social', 0, 1),
('travel-buddy', 'Travel buddy', 'travel', 0, 1),
('skill-exchange', 'Skill exchange', 'skills', 0, 1),
('support-circle', 'Support circle', 'support', 0, 1),
('wellbeing-buddy', 'Wellbeing buddy', 'wellbeing', 0, 1),
('dating', 'Dating', 'dating', 0, 1),
('private-connection', 'Private connection', 'social', 1, 1)
ON DUPLICATE KEY UPDATE label = VALUES(label), category = VALUES(category), requires_extra_privacy = VALUES(requires_extra_privacy), is_active = VALUES(is_active);
