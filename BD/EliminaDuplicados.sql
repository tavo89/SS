
DELETE a FROM names a, names b WHERE a.id > b.id AND a.name = b.name


-- art compra
SET @num_fac_com = '1639';
SET @nit_pro = '25052024';
SET @cod_su = 1;

SELECT des, a.ref,a.cod_barras,a.fecha_vencimiento,a.cod_su FROM art_fac_com a 
INNER JOIN 
(	SELECT count(*) as n, SUM(cant) as cant,SUM(unidades_fraccion) as unidades_fraccion,ref,cod_barras,fecha_vencimiento,cod_su 
	FROM `art_fac_com` WHERE num_fac_com=@num_fac_com AND nit_pro=@nit_pro AND cod_su=@cod_su 
	group by `ref`,`cod_barras`,`fecha_vencimiento`,`cod_su` having n>1
	) b
ON a.ref=b.ref AND a.cod_barras=b.cod_barras AND a.fecha_vencimiento=b.fecha_vencimiento AND a.cod_su=b.cod_su
WHERE a.num_fac_com=@num_fac_com AND a.nit_pro=@nit_pro AND a.cod_su=@cod_su ;



SET @num_fac_com = '1639';
SET @nit_pro = '25052024';
SET @cod_su = 1;

UPDATE art_fac_com a 
INNER JOIN 
(	SELECT count(*) as n, SUM(cant) as cant,SUM(unidades_fraccion) as unidades_fraccion,ref,cod_barras,fecha_vencimiento,cod_su 
	FROM `art_fac_com` WHERE num_fac_com=@num_fac_com AND nit_pro=@nit_pro AND cod_su=@cod_su  
	group by `ref`,`cod_barras`,`fecha_vencimiento`,`cod_su` having n>1
	) b
ON a.ref=b.ref AND a.cod_barras=b.cod_barras AND a.fecha_vencimiento=b.fecha_vencimiento AND a.cod_su=b.cod_su
SET a.cant=b.cant, a.unidades_fraccion = b.unidades_fraccion
WHERE a.num_fac_com=@num_fac_com AND a.nit_pro=@nit_pro AND a.cod_su=@cod_su;



SET @num_fac_com = '1639';
SET @nit_pro = '25052024';
SET @cod_su = 1;

DELETE FROM art_fac_com
 WHERE (num_fac_com=@num_fac_com AND nit_pro=@nit_pro AND cod_su=@cod_su) AND id NOT IN (SELECT * 
                    FROM (SELECT MAX(n.id)
                            FROM art_fac_com n
							WHERE n.num_fac_com=@num_fac_com AND n.nit_pro=@nit_pro AND n.cod_su=@cod_su
                        GROUP BY n.ref,n.cod_barras,n.fecha_vencimiento,n.cod_su) x);