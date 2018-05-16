# BRM_recurrence
Módulo custom para drupal para gestionar recurrecnias en ubercart

## Cómo funciona

El módulo esta creado para implementar la recurrencias de las ordenes extendiendo el funcionamiento de ubercart

Al momento de seleccionar una frecuencia se guardan en la tabla "brm_recurrence" para almacenar las ordenes en las cuales se seleccionó un tiempo para recurrencia, al momento de almacenar se guardan en "n" y al momento de que PayU responda se actualiza  a 'y' que hace que las ordenes se creen automaticamente.

### Cómo se crean las ordenes

Las ordenes son generadas al momento de ejecución del cron que es configurable desde 

```
admin/store/settings/brm-recurrence
```
 En donde se encuentran lo siguientes campos
 
*  Hour to request - Campo para configurar la hora de ejecución del cron para crear las ordenes
* Hout to down the 'bandera' - Campo para configurar el cron que baja la bandera
* Mail information - Campo para agregar un mail al cual se le confirma la creación de las ordenes recurrentes (opcional)

#### Qué es la bandera

La bandera es un registro en una tabla en la cual se guarda un registro en "N" o en "S" lo cual permite la ejecución siempre y cuando la bandera se encuentre en "S" para evitar la creación recurrentes de las ordenes estipuladas para el día

## Componentes

* js - recurrence.js
* theme - recurrence.tpl.php
* BRM_recurrence.module - Configuración del módulo
* BRM_recurrence.install - Instable inicial del módulo
* BRM_recurrence.info - Información básica del módulo

## Authors

* **Cristian Tangarife** - *Repositorio* - [BRM recurrence](https://github.com/brm-tangarifec/BRM_recurrence)