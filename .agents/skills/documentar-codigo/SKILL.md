---
name: documentar-codigo
description: Crea o actualiza documentación técnica a partir del código y las pruebas reales cuando se necesita explicar uso, parámetros, errores y ejemplos de una API o módulo.
---

# Documentar código

1. Leer el módulo solicitado y sus pruebas antes de escribir.
2. Crear o actualizar `docs/guia-uso.md` en español, incluyendo objetivo, firma, parámetros, respuesta, errores y un ejemplo ejecutable.
3. Reflejar exclusivamente el comportamiento comprobado en los archivos. No inventar funcionalidades.
4. No cambiar el código de producción ni las pruebas para ajustar la documentación.
5. Entregar el archivo generado y un resumen de lo documentado.

## Reglas obligatorias de este proyecto

- Leer `AGENTS.md` desde la raíz del proyecto y aplicar sus reglas antes de cualquier paso de esta skill. Estas restricciones prevalecen sobre las instrucciones de ejecutar pruebas o crear documentación.
- No ejecutar consultas, respaldos, migraciones, Tinker, factories, pruebas ni ningún comando o herramienta que se conecte a una base de datos sin autorización previa y explícita del usuario para esa operación, incluso si utiliza SQLite en memoria.
- No cambiar `.env`, credenciales ni conexiones; no limpiar la caché ni cambiar de conexión para sortear un bloqueo. Solicitar autorización cuando un paso dependa de la base de datos; continuar únicamente con trabajo independiente sin conexiones.
- Los archivos generados para pruebas van en `/pruebas`. Usar Pest para pruebas de Laravel y respetar las skills existentes del proyecto.
- Crear documentación solo por solicitud explícita del usuario y dentro de la estructura autorizada.
- Todo cambio de interfaz debe ser responsive, permitir solo desplazamiento vertical y conservar el navbar y la vista de escritorio desde XL (1280 px).

Origen: https://github.com/render2web/codex-5-skills-render2web (commit `2381322e96b31acb1b6553267a871e7a6e4b3e7c`). Adaptación local para CCDItmp.
