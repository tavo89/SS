




UPDATE `fac_venta` SET anulado='ANULADO', fecha_anula=NOW()
WHERE nit=1 AND anulado!='ANULADO' AND prefijo='FPOS' AND DATE(fecha) >='2024-06-27' AND DATE(fecha)<='2024-06-27';