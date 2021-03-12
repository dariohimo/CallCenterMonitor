# CallCenterMonitor

Es una aplicación php web para monitorear colas en Issabel/Asterisk. Funciona para canales definidos como operadores directamente con el tipo de tecnología, ej: SIP/201, IAX/301.

Utiliza memcached para guardar los datos en memoria y minimizar de esa manera las consultas al AMI de Asterisk, el proceso es:

Debe existir el usuario y grupo asterisk en el S.O.

1. Procesar de datos:

   Consulta al AMI -> Procesar datos de las colas -> Guardar datos a memoria

2. Presentación de datos mediante aplicación web MVC:

   Lectura de datos desde memoria -> Presentación web de los datos.


## Instalacion (Centos 7)

Instalar utilitarios:
```bash
yum install memcached libmemcached php-pecl-memcached composer httpd php
```

Clonar la aplicación:
```bash
cd /opt
git clone https://github.com/neovoice/CallCenterMonitor.git
ln -s /opt/CallCenterMonitor/QueueMonitor /var/www/html/QueueMonitor
chown -R asterisk. /opt/CallCenterMonitor
chown -R asterisk. /var/www/html/QueueMonitor
```

Editar /etc/sysconfig/memcached y modificar la línea OPTIONS para quedar así:
```
OPTIONS="-l 127.0.0.1 -U 0"**
```

Habilitar e iniciar el servicio memcached:
```bash
systemctl enable --now memcached
```

Preparar la aplicación:
```bash
su - asterisk
cd /opt/CallCenterMonitor
composer update
```

Editar la clase de conexión al AMI para incluir un comando, con vim abrir el fichero **/opt/CallCenterMonitor/vendor/welltime/phpagi/src/phpagi-asmanager.php** y localizar el texto:
```php
    function Command($command, $actionid=NULL)
    {
      $parameters = array('Command'=>$command);
      if($actionid) $parameters['ActionID'] = $actionid;
      return $this->send_request('Command', $parameters);
    }
```
Agregar a continuación y guardar:
```php
   /**
    * Core Show Channels
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+ZapShowChannels
    * @param string $actionid message matching variable
    */
    function CoreShowChannels($actionid=NULL)
    {
      if($actionid)
        return $this->send_request('CoreShowChannels', array('ActionID'=>$actionid));
      else
        return $this->send_request('CoreShowChannels');
    }
```
Crear el servicio de lectura de información:
```bash
cp /opt/CallCenterMonitor/callcentermonitor.service /etc/systemd/system/
systemctl daemon-reload
systemctl enable --now callcentermonitor
```

Únicamente para Issabel, habilitar el htaccess en la carpeta QueueMonitor. Editar el fichero **/etc/httpd/conf.d/issabel-htaccess.conf** y agregar al final del mismo:
```
<Directory "/var/www/html/QueueMonitor">
    AllowOverride All
</Directory>
```

Recargar apache para incluir la librería memcached y las modificaciones de configuración:
```bash
systemctl reload httpd
```

## Uso 
Ingresar al url web, Ej: **http://127.0.0.1/QueueMonitor/**

![ejemplo](https://github.com/neovoice/CallCenterMonitor/blob/master/example.png?raw=true "ejemplo")

## Colaboración
Este proyecto está abierto a contribuciones y mejoras por quien desee aportar al mismo.

## Licencia
[MIT](https://choosealicense.com/licenses/mit/)
