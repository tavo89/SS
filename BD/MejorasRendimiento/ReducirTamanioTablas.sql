


-- fac_venta`
ALTER TABLE `fac_venta` CHANGE `hash` `hash` VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;

ALTER TABLE `fac_venta` CHANGE `tel_cli` `tel_cli` VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `fac_venta` CHANGE `id_cli` `id_cli` VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `fac_venta` CHANGE `tipo_venta` `tipo_venta` VARCHAR(22) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;

ALTER TABLE `fac_venta` CHANGE `anulado` `anulado` VARCHAR(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `fac_venta` CHANGE `serial_fac` `serial_fac` MEDIUMINT(7) NOT NULL AUTO_INCREMENT;
ALTER TABLE `fac_venta` CHANGE `num_fac_ven` `num_fac_ven` MEDIUMINT(7) NOT NULL;
ALTER TABLE `fac_venta` CHANGE `vendedor` `vendedor` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;


-- art_fac_ven
ALTER TABLE `art_fac_ven` CHANGE `nit` `nit` TINYINT(1) NOT NULL;
ALTER TABLE `art_fac_ven` CHANGE `hash` `hash` VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;