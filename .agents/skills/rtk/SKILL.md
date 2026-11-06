---
name: rtk
description: Usa RTK para compactar la salida de comandos de desarrollo autorizados en CCDItmp, como consultas de Git y lectura de archivos, cuando conviene reducir el contexto de terminal.
---

# RTK en CCDItmp

Integracion local para Codex basada en https://github.com/rtk-ai/rtk.
El repositorio oficial distribuye una CLI; este SKILL.md es una adaptacion local, no una skill oficial publicada por RTK.

## Instalacion

- Ejecutable portable: `.agents/skills/rtk/bin/rtk.exe`, relativo a la raiz del proyecto.
- Version instalada: `v1.1.0`, Windows x86_64, paquete oficial `rtk-x86_64-pc-windows-msvc.zip`.
- SHA-256 del paquete: `c9a8f7a8bad471e64a343314168f38d2399a06fa327cdf5772fb0e645d99ea6f`.
- No hay hooks globales, servicios, proxy ni cambios al PATH. La invocacion es explicita.
- Documentacion: https://github.com/rtk-ai/rtk/blob/v1.1.0/INSTALL.md

## Autorizacion obligatoria

1. Leer `AGENTS.md` antes de usar RTK. Comprimir la salida no cambia los permisos del comando subyacente.
2. RTK registra sus ejecuciones en una base SQLite propia. Antes de cualquier comando que pueda inicializar ese registro, obtener autorizacion explicita del usuario para crear y usar exclusivamente `.agents/skills/rtk/data/history.db` como estadisticas locales de RTK.
3. Instalar esta skill NO concede autorizacion para bases de datos. Si falta el permiso del punto anterior, dejar RTK instalado sin ejecutarlo y usar herramientas normales permitidas.
4. El permiso para la SQLite de RTK nunca autoriza acceso a MySQL, Laravel ni a la base del sistema. Consultas, respaldos, migraciones, seeders, Tinker y pruebas con cualquier base requieren su propia autorizacion.
5. No ejecutar `rtk gain`, `rtk discover`, `rtk session` ni comandos de estadisticas sin permiso de acceso a la SQLite de RTK.
6. No cambiar `.env`, credenciales o conexiones, ni limpiar caches para sortear bloqueos. No instalar hooks, proxy o integraciones globales sin solicitud especifica.

## Uso una vez autorizado el registro SQLite de RTK

Configurar solo el entorno del proceso de terminal, sin editar `.env` ni variables permanentes:

```powershell
$env:RTK_DB_PATH = Join-Path (Get-Location).Path '.agents/skills/rtk/data/history.db'
$env:RTK_RECALL = '0'
$env:RTK_TEE = '0'
& ./.agents/skills/rtk/bin/rtk.exe git status
```

- Los parametros RTK_RECALL y RTK_TEE desactivan la recuperacion de salidas completas; no desactivan las estadisticas SQLite.
- Preferir usos de lectura ya autorizados: `git status`, `git diff`, `git log`, `ls` y `read`.
- No convertir una revision en `git add`, `git commit`, `git push` o ejecucion de pruebas sin autorizacion aplicable.
- No activar telemetria ni transmitir salidas a servicios externos.
- Si la salida compactada omite informacion necesaria para revisar un error o un diff, consultar el comando original sin compresion, respetando los mismos permisos.
- Mantener las vistas responsive y sin scroll horizontal en cualquier cambio posterior; conservar escritorio desde XL.
