-- Demo users for roles
INSERT IGNORE INTO `user` (`id`, `iduser_level`, `nama`, `username`, `password`) VALUES
(200, 100, 'admin prov demo', 'adminprov_demo', 'adminprov'),
(201, 101, 'admin kab demo', 'adminkab_demo', 'adminkab'),
(202, 102, 'hafidz demo', 'hafidz_demo', 'hafidz123');

-- Note: Ensure user IDs do not conflict with existing ones in your database.
