diccionario = {
#(IZQUIERDA = CLAVE(KEY)) : (DERECHA = VALOR(VALUE))
    "nombre" : "Lucas", #ELEMENTO 1
    "apellido" : "Dalto", #ELEMENTO 2
    "subs": 100000 #ELEMENTO 3
}

### METODOS PARA DICCIONARIOS ###

## KEYS ##
#Nos va a devolver las claves del diccionario (dict_item(objeto que se puede iterar))
clave = diccionario.keys()

## GETS ##
#Nos va a devolver un valor de una clave

#La diferencia entre la de arriba y la de abajo,es que la de arriba nos va a tirar un excepcion, y para el programa.
#Mientras que el metodo,nos va a retornar "None" y el programa si funcionando

#valor1 = diccionario["apellido"]
valor1 = diccionario.get("apellido")

## POP ##
#Funciona como en la lista, solo que se le da como parametro la CLAVE
diccionario.pop("nombre")


## ITEMS ##
#Obtenemos un elemento dict_item
diccionario_iterable = diccionario.items()

## CLEAR ##
#Elimina todos los elementos del diccionario

print(diccionario)
print(diccionario_iterable)