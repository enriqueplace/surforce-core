Evolucionando la idea del proyecto [SURFORCE-BASE](http://code.google.com/p/surforce-base/) que funcionaba como un "template" para poder comenzar un proyecto de cero (pero luego este evolucionaba separadamente de la versión original), SURFORCE-CORE busca generar una única plataforma genérica para compartir entre proyectos, donde lo único que cambiará será sus archivos de configuración y los módulos particulares que puedan agregarse.

Todas nuestras aplicaciones podrán compartir el mismo estilo, funcionalidad de login, menúes dinámicos, etc, y todas las mejoras afectarán a todos los sistemas por igual, logrando el objetivo de disminuir los costos de desarrollo al evitar implementar funcionalidades repetitivas entre sistemas.

Finalmente, obtendremos un "corazón" (core) para nuestros sistemas.

Nota: no necesariamente se aplicaría a todos nuestros sistemas que queramos desarrollar, pero sí a todos nuestros sistemas que son "similares" en funcionalidades pero que se aplican en contextos distintos y no necesariamente son parte del mismo sistema (admins, estadísticas, etc).