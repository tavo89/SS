

UPDATE inv_inter a INNER JOIN (SELECT count(*) as n, SUM(exist) as exist,id_pro,id_inter,nit_scs 
FROM `inv_inter`  group by `id_pro`,`id_inter`,`nit_scs` having n>1) b ON a.id_pro=b.id_pro AND a.id_inter=b.id_inter AND a.nit_scs=b.nit_scs  SET a.exist=b.exist;


DELETE FROM inv_inter
 WHERE serial_inv NOT IN (SELECT * 
                    FROM (SELECT MAX(n.serial_inv)
                            FROM inv_inter n
                        GROUP BY n.id_pro,n.id_inter,n.nit_scs) x);
						
						UPDATE inv_inter SET `fecha_vencimiento`='0000-00-00' WHERE 1;
						
						

						
						
DELETE FROM `inv_inter` WHERE `id_pro` NOT IN(SELECT `id_pro` FROM productos);
DELETE FROM `productos` WHERE `id_pro` NOT IN (SELECT `id_pro` FROM inv_inter);