## Cosas a tener en cuenta sobre cadena de texto


cadena1 = "Hola matarin lerin leron"

cadena2 = "Cadena numero 2 que sirve como ejemplo"

## DIFERENCIA ENTRE METODOS Y FUNCIONES
####METODO####
#Son funciones especificas de objetos

####FUNCIONES####
#No necesariamente tiene que ser de objeto

print(dir(cadena1))
# la funcion "dir" nos va decir todas los metodos que podemos aplicar al tipo de dato
#resultado = DATO.METODO()

####UPPER###
#Convierte todo el dato en Mayuscula
mayusc = cadena1.upper()

####LOWER###
#Convierte todo el dato en minuscula
minusc = cadena1.lower()

####CAPITALIZE###
#Primer letra en mayuscula(este metodo convierte todo a minuscula y luego convierte la primera en Mayus)
primer_letra = cadena1.capitalize()


####FIND###
#Sirve para encontrar algo dentro de la cadena y nos retornara la ubicacion teniendo en cuenta
#la iteracion de matriz(comenzando desde 0)...retornara "-1" si no hay coincidencia
busqueda_find = cadena1.find("d")#Aca ya agregamos parametro


####FIND###
#Funciona similar a "find" solo que nos tirara un "Exception" si no hay coincidencia
busqueda_index = cadena1.index("d")

####ISNUMERIC###
#nos devuelve "true" si es numerico
isnumeric = cadena1.isnumeric()

####ISALPHA###
#Nos va a devolver true solo si el dato contiene solo caracteres alfanumericos(a-z) sin espacios
es_alphanumerico = cadena1.isalpha()


####COUNT###
#Nos devuelve la cantidad de veces que coincida el "parametro" con el "dato"
contar_coincidencia = cadena1.count("a")

####LEN###
#Devuelve la cantidad de caracteres que tiene el "dato"
contar_caracteres = len(cadena1)
#len es una funcion, por eso si vemos no señalamos primero el "dato" sino lo ponemos como parametro

####ENDSWITH###
#Retorna "True" si el "dato" coincide con el "parametro" dado. ENDSWITH(Termina con)
termina_con = cadena1.endswith("ron")
####STARTWITH###
#Nos va a retornar "True" si el "dato" coincide con el "parametro" dado. STARTWITH(Comienza con)
comienza_con = cadena1.startswith("Ho")

####REPLACE###
#Remplaza un "parametro" del "dato" por otro "parametro" nuevo
#Funciona solo si el valor 1("Hola"), coincide con el dato original, entonces
#remplaza valor1("Hola") por valor2("Remplazo")
remplazar_con = cadena1.replace("Hola", "Remplazo")

####SPLIT###
#Separa el "dato" en una "lista" a travez del "parametro" dado
separado_por = cadena1.split(" ")
#Esto nos puede ayudar a iterar una cadena muy larga

print()#Aca ingresamos el metodo que querramos probar