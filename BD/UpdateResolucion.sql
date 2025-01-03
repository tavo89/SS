

UPDATE `fac_venta` 
SET resolucion='18764086567215',
    fecha_resol='2024-12-31',
    rango_resol='(61 - 500)'
WHERE num_fac_ven>=61  AND prefijo='FE' AND nit=1;