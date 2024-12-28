


----- unitario
SET @producto='7702354032319';
SET @sucursal=1;


UPDATE art_ajuste SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE ref=@producto AND cod_barras=@producto AND cod_su=@sucursal;

UPDATE `art_fac_com` SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE (ref=@producto AND cod_barras=@producto AND cod_su=@sucursal) AND (num_fac_com!='AR-15240');

UPDATE `art_fac_ven` SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE ref=@producto AND cod_barras=@producto AND nit=@sucursal;


----- total !!!
SET @sucursal=8;
UPDATE `art_fac_com` SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE  cod_su=@sucursal AND (num_fac_com!='8' AND nit_pro!='1');


SET @sucursal=8;
UPDATE  art_devolucion_venta SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE nit=@sucursal;
UPDATE  art_ajuste SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE cod_su=@sucursal;
UPDATE `art_fac_ven` SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE  nit=@sucursal;
UPDATE `art_fac_remi` SET ref=CONCAT(ref,'OLD'), cod_barras=CONCAT(cod_barras,'OLD') WHERE  nit=@sucursal;
UPDATE `fac_remi` SET sede_destino=88 WHERE sede_destino=@sucursal;



--- rollback


UPDATE table SET fieldname=REPLACE(fieldname,'APS','');

SET @sucursal=1;
UPDATE inv_inter SET `id_pro`=REPLACE(`id_pro`,'old',''), `id_inter`=REPLACE(`id_inter`,'old','');
UPDATE inv_inter SET `id_pro`=REPLACE(`id_pro`,'OLD',''), `id_inter`=REPLACE(`id_inter`,'OLD','');


SET @sucursal=1;
UPDATE `art_fac_ven` a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit 
SET a.ref=REPLACE(a.ref,'old',''), a.cod_barras=REPLACE(a.cod_barras,'old','') WHERE  a.nit=@sucursal AND b.fecha>='2024-05-05';
UPDATE `art_fac_ven` a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit 
SET a.ref=REPLACE(a.ref,'OLD',''), a.cod_barras=REPLACE(a.cod_barras,'OLD','') WHERE  a.nit=@sucursal AND b.fecha>='2024-05-05';



SET @sucursal=1;
UPDATE `art_fac_com` SET ref=REPLACE(ref,'old',''), cod_barras=REPLACE(cod_barras,'old','') WHERE  cod_su=@sucursal AND (num_fac_com='5' AND nit_pro='1');
UPDATE `art_fac_com` SET ref=REPLACE(ref,'OLD',''), cod_barras=REPLACE(cod_barras,'OLD','') WHERE  cod_su=@sucursal AND (num_fac_com='5' AND nit_pro='1');