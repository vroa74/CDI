---
name: preparar-entrega
description: Prepara un resumen para revisión o pull request después de cambios de código, pruebas o documentación, verificando el estado Git y los controles disponibles.
---

# Preparar entrega

1. Ejecutar `git status --short`, `git diff --check` y `git diff --stat`; incluir archivos no seguidos al resumir el alcance.
2. Ejecutar el comando de pruebas disponible e indicar el resultado real.
3. Proponer un título breve de pull request y un resumen de cambios.
4. Enumerar pruebas ejecutadas, riesgos pendientes y lista breve para revisión humana.
5. No ejecutar `git add`, `git commit`, `git push`, crear PR ni afirmar que hubo despliegue, salvo autorización explícita.

## Reglas obligatorias de este proyecto

- Leer `AGENTS.md` desde la raíz del proyecto y aplicar sus reglas antes de cualquier paso de esta skill. Estas restricciones prevalecen sobre las instrucciones de ejecutar pruebas o crear documentación.
- No ejecutar consultas, respaldos, migraciones, Tinker, factories, pruebas ni ningún comando o herramienta que se conecte a una base de datos sin autorización previa y explícita del usuario para esa operación, incluso si utiliza SQLite en memoria.
- No cambiar `.env`, credenciales ni conexiones; no limpiar la caché ni cambiar de conexión para sortear un bloqueo. Solicitar autorización cuando un paso dependa de la base de datos; continuar únicamente con trabajo independiente sin conexiones.
- Los archivos generados para pruebas van en `/pruebas`. Usar Pest para pruebas de Laravel y respetar las skills existentes del proyecto.
- Crear documentación solo por solicitud explícita del usuario y dentro de la estructura autorizada.
- Todo cambio de interfaz debe ser responsive, permitir solo desplazamiento vertical y conservar el navbar y la vista de escritorio desde XL (1280 px).

Origen: https://github.com/render2web/codex-5-skills-render2web (commit `2381322e96b31acb1b6553267a871e7a6e4b3e7c`). Adaptación local para CCDItmp.
