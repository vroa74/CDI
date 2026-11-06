---
name: revisar-codigo
description: Revisa cambios de código para detectar errores, regresiones, problemas de seguridad y falta de cobertura cuando se solicita una revisión de un módulo o un diff.
---

# Revisar código

1. Leer el alcance solicitado, el diff, las reglas del proyecto y los archivos relacionados antes de concluir.
2. Buscar defectos verificables, regresiones, problemas de autorización, riesgos para los datos y cambios de comportamiento no solicitados.
3. En vistas y componentes, comprobar la adaptación responsive, los modales y la ausencia de desplazamiento horizontal; conservar el escritorio desde XL.
4. Revisar las pruebas existentes sin ejecutarlas cuando requieran una base de datos y no exista autorización explícita del usuario.
5. Presentar hallazgos por gravedad con archivo, línea, consecuencia y evidencia. Separar hechos comprobados de dudas y declarar los límites de la revisión.
6. No modificar archivos ni realizar commits como parte de una revisión, salvo solicitud explícita.

## Reglas obligatorias de este proyecto

- Leer `AGENTS.md` desde la raíz del proyecto y aplicar sus reglas antes de cualquier paso de esta skill. Estas restricciones prevalecen sobre las instrucciones de ejecutar pruebas o crear documentación.
- No ejecutar consultas, respaldos, migraciones, Tinker, factories, pruebas ni ningún comando o herramienta que se conecte a una base de datos sin autorización previa y explícita del usuario para esa operación, incluso si utiliza SQLite en memoria.
- No cambiar `.env`, credenciales ni conexiones; no limpiar la caché ni cambiar de conexión para sortear un bloqueo. Solicitar autorización cuando un paso dependa de la base de datos; continuar únicamente con trabajo independiente sin conexiones.
- Los archivos generados para pruebas van en `/pruebas`. Usar Pest para pruebas de Laravel y respetar las skills existentes del proyecto.
- Crear documentación solo por solicitud explícita del usuario y dentro de la estructura autorizada.
- Todo cambio de interfaz debe ser responsive, permitir solo desplazamiento vertical y conservar el navbar y la vista de escritorio desde XL (1280 px).

Origen: https://github.com/render2web/codex-5-skills-render2web (commit `2381322e96b31acb1b6553267a871e7a6e4b3e7c`). Adaptación local para CCDItmp. Se reemplazó la copia errónea de documentar-codigo del repositorio original.
