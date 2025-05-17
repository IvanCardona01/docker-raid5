#!/bin/bash
echo "⏳ Copiando archivos PHP a /mnt/lvm/apache ..."

if [ ! -f /mnt/lvm/apache/form.php ]; then
  cp ~/arquitectura_contenedores/www/*.php /mnt/lvm/apache/
  
  if [ -d ~/arquitectura_contenedores/vendor ]; then
    cp -r ~/arquitectura_contenedores/vendor /mnt/lvm/apache/
  else
    echo "⚠️ No se encontró la carpeta 'vendor'. Ejecuta 'composer install' en /home/ivan/arquitectura_contenedores antes de iniciar."
  fi

  echo "✅ Archivos copiados a /mnt/lvm/apache/"
else
  echo "ℹ️ Archivos ya existen en /mnt/lvm/apache/, no se copiaron."
fi
