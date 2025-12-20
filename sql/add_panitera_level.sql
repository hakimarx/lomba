-- Add Panitera user level if not exists
INSERT IGNORE INTO user_level (id, nama, role) VALUES
(103, 'panitera', 'panitera');

-- Verify user levels exist
SELECT * FROM user_level ORDER BY id;
