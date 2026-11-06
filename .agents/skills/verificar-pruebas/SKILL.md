---
name: verificar-pruebas
description: Comprueba y amplía las pruebas automatizadas de un proyecto cuando se necesita validar cambios, cubrir casos límite o investigar una regresión.
---

# Verificar pruebas

1. Detectar el entorno y el comando de pruebas real del repositorio (por ejemplo `npm test`, `dotnet test` o `pytest`).
2. Ejecutar primero las pruebas existentes e informar su estado de partida.
3. Si se solicita cobertura adicional, agregar pruebas pequeñas para casos normales y límites sin modificar código productivo solo para forzar un resultado verde.
4. Ejecutar nuevamente las pruebas y detallar resultados, fallos y archivos modificados.
5. No instalar paquetes ni alterar configuración sin justificación y aprobación.

## Reglas obligatorias de este proyecto

- Leer `AGENTS.md` desde la raíz del proyecto y aplicar sus reglas antes de cualquier paso de esta skill. Estas restricciones prevalecen sobre las instrucciones de ejecutar pruebas o crear documentación.
- No ejecutar consultas, respaldos, migraciones, Tinker, factories, pruebas ni ningún comando o herramienta que se conecte a una base de datos sin autorización previa y explícita del usuario para esa operación, incluso si utiliza SQLite en memoria.
- No cambiar `.env`, credenciales ni conexiones; no limpiar la caché ni cambiar de conexión para sortear un bloqueo. Solicitar autorización cuando un paso dependa de la base de datos; continuar únicamente con trabajo independiente sin conexiones.
- Los archivos generados para pruebas van en `/pruebas`. Usar Pest para pruebas de Laravel y respetar las skills existentes del proyecto.
- Crear documentación solo por solicitud explícita del usuario y dentro de la estructura autorizada.
- Todo cambio de interfaz debe ser responsive, permitir solo desplazamiento vertical y conservar el navbar y la vista de escritorio desde XL (1280 px).

Origen: https://github.com/render2web/codex-5-skills-render2web (commit `2381322e96b31acb1b6553267a871e7a6e4b3e7c`). Adaptación local para CCDItmp.
