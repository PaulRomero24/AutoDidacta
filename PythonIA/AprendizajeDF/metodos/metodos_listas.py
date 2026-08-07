### METODOS O FUNCIONES PARA LISTAS ###

##LIST##
#crea una lista list()
lista = list([23,123,21,24])


## LEN ##
#Cuenta la cantidad de elementos que tiene la lista
resultado = len(lista)

## APPEND ##
#Agrega un elemento a la lista
lista.append("AVER")

## INSERT ##
#Agrega un elemento a la lista,pero con un indice especifico
lista.insert(2,"AQUILES")#Primero pasamos el indice, luego el elemento que querramos agregar

## EXTENDS ##
# Sirve para agregar una lista dentro de otra.
lista.extend([3,1])


## POP ##
#Elimina un elemento por indice
lista.pop(2)
#Truco para borrar elementos desde el final de la lista, en caso de que la lista sea muy larga
lista.pop(-1)#Como funciona? la lista se iteran de 0 en adelante, pero nunca negativamente, entonces al indicar un "-1" toma como valor el ultimo elemento  (-1 , 0 , 1)


## REMOVE ##
#Elimina un elemento por su valor. Solo si hay coincidencia
lista.remove("AVER")

## CLEAR ##
#Elimina todos los elementos de la lista
# lista.clear()

## SORT ##
#Ordena la lista de forma ascendente, por ende no pueden haber string en la lista. Se pueden incluir booleanos(como primer valor siempre "False" luego "True")
lista.sort()# Para ordenarla de forma descendente se agrega como parametro (reverse=True)

## REVERSE ##
#Invierte los elementos de la lista
lista.reverse()
#Es medio choto el ejemplo despues de sort, que ordena la lista, pero para saber. Puede invertir los elementos sin necesidad de ordenarlos

## INDEX ##
#Funciona similar al metodo de "string", solo que en vez de buscar un un valor, busca un elemento
elemento_index = lista.index(123)

print(lista)