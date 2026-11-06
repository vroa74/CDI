---
name: refactorizar-seguro
description: Refactoriza código para mejorar legibilidad y organización sin cambiar el comportamiento observable, comprobándolo con pruebas antes y después.
---

# Refactorizar de forma segura

1. Revisar el módulo indicado y ejecutar pruebas existentes como línea base.
2. Identificar cambios internos pequeños que mejoren nombres, estructura o duplicación.
3. Mantener firmas públicas, resultados, errores y reglas de negocio existentes.
4. Realizar el refactor mínimo y volver a ejecutar las pruebas.
5. Revisar `git diff` y describir modificaciones, pruebas y límites de la validación.
6. No introducir correcciones funcionales encubiertas: tratarlas como tareas aparte.

## Reglas obligatorias de este proyecto

- Leer `AGENTS.md` desde la raíz del proyecto y aplicar sus reglas antes de cualquier paso de esta skill. Estas restricciones prevalecen sobre las instrucciones de ejecutar pruebas o crear documentación.
- No ejecutar consultas, respaldos, migraciones, Tinker, factories, pruebas ni ningún comando o herramienta que se conecte a una base de datos sin autorización previa y explícita del usuario para esa operación, incluso si utiliza SQLite en memoria.
- No cambiar `.env`, credenciales ni conexiones; no limpiar la caché ni cambiar de conexión para sortear un bloqueo. Solicitar autorización cuando un paso dependa de la base de datos; continuar únicamente con trabajo independiente sin conexiones.
- Los archivos generados para pruebas van en `/pruebas`. Usar Pest para pruebas de Laravel y respetar las skills existentes del proyecto.
- Crear documentación solo por solicitud explícita del usuario y dentro de la estructura autorizada.
- Todo cambio de interfaz debe ser responsive, permitir solo desplazamiento vertical y conservar el navbar y la vista de escritorio desde XL (1280 px).

Origen: https://github.com/render2web/codex-5-skills-render2web (commit `2381322e96b31acb1b6553267a871e7a6e4b3e7c`). Adaptación local para CCDItmp.
